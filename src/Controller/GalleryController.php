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

    #[Route('/', name: 'app_gallery_index', methods: ['GET'])]
    public function index(GalleryRepository $galleryRepository): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw $this->createAccessDeniedException();
        }

        $galleries = $galleryRepository->findByUserOrdered($user);

        return $this->render('gallery/index.html.twig', [
            'galleries' => $galleries,
        ]);
    }

    #[Route('/new', name: 'app_gallery_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User) throw $this->createAccessDeniedException();

        $gallery = new Gallery();
        $form = $this->createForm(GalleryFormType::class, $gallery);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('imagePath')->getData();
            if ($imageFile) {
                $gallery->setImagePath($this->handleUpload($imageFile));
            }

            $gallery->setUser($user);
            $em->persist($gallery);
            $em->flush();

            $this->addFlash('success', 'Image ajoutée à la galerie!');
            return $this->redirectToRoute('app_gallery_index');
        }

        return $this->render('gallery/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_gallery_show', methods: ['GET'])]
    public function show(Gallery $gallery): Response
    {
        $user = $this->getUser();
        if ($gallery->getUser() !== $user) {
            throw $this->createAccessDeniedException('Vous n\'avez pas accès à cette ressource.');
        }

        return $this->render('gallery/show.html.twig', [
            'gallery' => $gallery,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_gallery_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Gallery $gallery, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        if ($gallery->getUser() !== $user) {
            throw $this->createAccessDeniedException('Vous n\'avez pas accès à cette ressource.');
        }

        $form = $this->createForm(GalleryFormType::class, $gallery, ['require_image' => false]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('imagePath')->getData();
            if ($imageFile) {
                $gallery->setImagePath($this->handleUpload($imageFile));
            }

            $em->flush();
            $this->addFlash('success', 'Image mise à jour!');
            return $this->redirectToRoute('app_gallery_index');
        }

        return $this->render('gallery/edit.html.twig', [
            'gallery' => $gallery,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/delete', name: 'app_gallery_delete', methods: ['POST'])]
    public function delete(Request $request, Gallery $gallery, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        if ($gallery->getUser() !== $user) {
            throw $this->createAccessDeniedException();
        }

        if ($this->isCsrfTokenValid('delete'.$gallery->getId(), $request->request->get('_token'))) {
            $em->remove($gallery);
            $em->flush();
            $this->addFlash('success', 'Image supprimée!');
        }

        return $this->redirectToRoute('app_gallery_index');
    }

    /**
     * Gère l’upload d’une image et retourne le chemin relatif
     */
    private function handleUpload(UploadedFile $file): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $extension = $file->guessExtension() ?? 'bin';
        $newFilename = $safeFilename.'-'.uniqid().'.'.$extension;

        $file->move(
            $this->getParameter('kernel.project_dir').'/public/'.$this->galleryDirectory,
            $newFilename
        );

        return $this->galleryDirectory.'/'.$newFilename;
    }
}
