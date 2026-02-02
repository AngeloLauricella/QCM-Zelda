<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\PlayerService;
use App\Service\ItemEffectService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/profile', name: 'app_profile_')]
#[IsGranted('ROLE_USER')]
class ProfileController extends AbstractController
{
    public function __construct(
        private PlayerService $playerService,
        private ItemEffectService $itemEffectService,
        private EntityManagerInterface $em,
        private ValidatorInterface $validator,
    ) {
    }

    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        $player = $this->playerService->getOrCreatePlayerForUser($this->getUser());
        $progress = $player->getCurrentProgress();

        if (!$progress) {
            return $this->redirectToRoute('game_index');
        }

        $equipment = $progress->getEquipment();
        $equippedItems = $equipment?->getEquippedItems() ?? [];

        return $this->render('profile/index.html.twig', [
            'player' => $player,
            'progress' => $progress,
            'equippedItems' => $equippedItems,
            'pointsMultiplier' => $equipment ? $this->itemEffectService->calculatePointsMultiplier($equipment) : 1.0,
        ]);
    }

    /**
     * Modifier les infos du profil utilisateur
     */
    #[Route('/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($request->isMethod('GET')) {
            return $this->render('profile/edit.html.twig', [
                'user' => $user,
            ]);
        }

        // Traiter le formulaire POST
        $newUsername = $request->request->getString('username', '');
        $newEmail = $request->request->getString('email', '');
        $profileImage = $request->files->get('profileImage');

        // Valider le username
        if (empty($newUsername)) {
            $this->addFlash('error', 'Le nom d\'utilisateur est obligatoire.');
            return $this->redirectToRoute('app_profile_edit');
        }

        if (strlen($newUsername) < 3 || strlen($newUsername) > 50) {
            $this->addFlash('error', 'Le nom d\'utilisateur doit contenir entre 3 et 50 caractères.');
            return $this->redirectToRoute('app_profile_edit');
        }

        // Valider l'email
        if (empty($newEmail) || !filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
            $this->addFlash('error', 'Veuillez fournir un email valide.');
            return $this->redirectToRoute('app_profile_edit');
        }

        // Vérifier que le username/email ne sont pas déjà utilisés par quelqu'un d'autre
        if ($newUsername !== $user->getUsername()) {
            $existingUser = $this->em->getRepository('App\Entity\User')
                ->findOneBy(['username' => $newUsername]);
            if ($existingUser) {
                $this->addFlash('error', 'Ce nom d\'utilisateur est déjà pris.');
                return $this->redirectToRoute('app_profile_edit');
            }
        }

        if ($newEmail !== $user->getEmail()) {
            $existingUser = $this->em->getRepository('App\Entity\User')
                ->findOneBy(['email' => $newEmail]);
            if ($existingUser) {
                $this->addFlash('error', 'Cet email est déjà utilisé.');
                return $this->redirectToRoute('app_profile_edit');
            }
        }

        // Mettre à jour le profil
        $user->setUsername($newUsername);
        $user->setEmail($newEmail);

        // Traiter l'upload de photo de profil
        if ($profileImage) {
            $fileName = $this->uploadProfileImage($profileImage);
            if ($fileName) {
                $user->setProfileImage($fileName);
            } else {
                $this->addFlash('error', 'Erreur lors de l\'upload de l\'image.');
                return $this->redirectToRoute('app_profile_edit');
            }
        }

        try {
            $this->em->flush();
            $this->addFlash('success', '✅ Votre profil a été mis à jour avec succès!');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Erreur lors de la mise à jour du profil.');
            return $this->redirectToRoute('app_profile_edit');
        }

        return $this->redirectToRoute('app_profile_index');
    }

    /**
     * Uploader et sauvegarder une image de profil
     */
    private function uploadProfileImage($file): ?string
    {
        /** @var User $user */
        $user = $this->getUser();
        try {
            // Valider le fichier
            $maxSize = 5 * 1024 * 1024; // 5MB
            if ($file->getSize() > $maxSize) {
                return null;
            }

            $mimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!in_array($file->getMimeType(), $mimeTypes)) {
                return null;
            }

            // Générer un nom unique
            $fileName = 'profile_' . $user->getId() . '_' . time() . '.' . $file->guessExtension();
            // Créer le répertoire s'il n'existe pas
            $uploadDir = $this->getParameter('kernel.project_dir') . '/public/uploads/profile';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // Déplacer le fichier
            $file->move($uploadDir, $fileName);

            // Supprimer l'ancienne image si elle existe
            if ($oldImage = $user->getProfileImage()) {
                $oldPath = $uploadDir . '/' . $oldImage;
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            return $fileName;
        } catch (\Exception $e) {
            return null;
        }
    }
}
