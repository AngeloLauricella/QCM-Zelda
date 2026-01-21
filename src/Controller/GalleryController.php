<?php

namespace App\Controller;

use App\Entity\Gallery;
use App\Form\GalleryFormType;
use App\Repository\GalleryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Entity\User;

#[Route('/gallery')]
#[IsGranted('ROLE_USER')]
class GalleryController extends AbstractController
{
    private string $galleryDirectory = 'uploads/gallery';

    public function __construct(private SluggerInterface $slugger)
    {
    }

    #[Route('', name: 'app_gallery_index', methods: ['GET'])]
    public function index(GalleryRepository $galleryRepository): Response
    {
        $user = $this->getUser();

        if (!$user instanceof \App\Entity\User) {
            throw $this->createAccessDeniedException();
        }

        // Passer l'entité User directement au repository
        $galleries = $galleryRepository->findByUserOrdered($user);

        return $this->render('gallery/index.html.twig', [
            'galleries' => $galleries,
        ]);
    }

    #[Route('/new', name: 'app_gallery_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        GalleryRepository $galleryRepository
    ): Response {
        $gallery = new Gallery();
        $form = $this->createForm(GalleryFormType::class, $gallery);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle file upload
            $imageFile = $form->get('imagePath')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $this->slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('kernel.project_dir') . '/public/' . $this->galleryDirectory,
                        $newFilename
                    );
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Erreur lors de l\'upload du fichier.');
                    return $this->redirectToRoute('app_gallery_index');
                }

                $gallery->setImagePath($this->galleryDirectory . '/' . $newFilename);
            }

            $gallery->setUser($this->getUser());
            $entityManager->persist($gallery);
            $entityManager->flush();

            $this->addFlash('success', 'Image ajoutée à la galerie!');
            return $this->redirectToRoute('app_gallery_index');
        }

        return $this->render('gallery/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_gallery_show', methods: ['GET'])]
    public function show(Gallery $gallery): Response
    {
        // Check if user owns this gallery item
        if ($gallery->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous n\'avez pas accès à cette ressource.');
        }

        return $this->render('gallery/show.html.twig', [
            'gallery' => $gallery,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_gallery_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Gallery $gallery,
        EntityManagerInterface $entityManager
    ): Response {
        // Check if user owns this gallery item
        if ($gallery->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous n\'avez pas accès à cette ressource.');
        }

        $form = $this->createForm(GalleryFormType::class, $gallery, [
            'require_image' => false,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle new file upload if provided
            $imageFile = $form->get('imagePath')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $this->slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('kernel.project_dir') . '/public/' . $this->galleryDirectory,
                        $newFilename
                    );
                    $gallery->setImagePath($this->galleryDirectory . '/' . $newFilename);
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Erreur lors de l\'upload du fichier.');
                    return $this->redirectToRoute('app_gallery_index');
                }
            }

            $entityManager->flush();

            $this->addFlash('success', 'Image mise à jour!');
            return $this->redirectToRoute('app_gallery_index');
        }

        return $this->render('gallery/edit.html.twig', [
            'gallery' => $gallery,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_gallery_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Gallery $gallery,
        EntityManagerInterface $entityManager
    ): Response {
        // Check if user owns this gallery item
        if ($gallery->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous n\'avez pas accès à cette ressource.');
        }

        if ($this->isCsrfTokenValid('delete' . $gallery->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($gallery);
            $entityManager->flush();

            $this->addFlash('success', 'Image supprimée!');
        }

        return $this->redirectToRoute('app_gallery_index');
    }
}
