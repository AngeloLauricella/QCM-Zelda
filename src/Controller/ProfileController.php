<?php

namespace App\Controller;

use App\Repository\GalleryRepository;
use App\Repository\ScoreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Entity\User;


#[Route('/profile')]
#[IsGranted('ROLE_USER')]
class ProfileController extends AbstractController
{
    #[Route('', name: 'app_profile', methods: ['GET'])]

    public function profile(
        ScoreRepository $scoreRepository,
        GalleryRepository $galleryRepository
    ): Response {
        $user = $this->getUser();

        // Vérification que l'utilisateur est bien ton entité User
        if (!$user instanceof \App\Entity\User) {
            throw $this->createAccessDeniedException();
        }

        // Passer directement l'entité User aux repositories
        $bestScore = $scoreRepository->getBestScore($user);
        $averageScore = $scoreRepository->getAverageScore($user);
        $totalScores = count($scoreRepository->findByUserOrdered($user));
        $galleries = $galleryRepository->findByUserOrdered($user);

        return $this->render('profile/index.html.twig', [
            'bestScore' => $bestScore,
            'averageScore' => round($averageScore ?? 0, 2),
            'totalScores' => $totalScores,
            'galleries' => $galleries,
        ]);
    }


}
