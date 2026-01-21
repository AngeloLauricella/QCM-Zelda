<?php

namespace App\Controller;

use App\Entity\Score;
use App\Repository\ScoreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Entity\User;

#[Route('/score')]
#[IsGranted('ROLE_USER')]
class ScoreController extends AbstractController
{
    #[Route('', name: 'app_score_index', methods: ['GET'])]
   public function index(ScoreRepository $scoreRepository): Response
{
    $user = $this->getUser();

    if (!$user instanceof User) {
        throw $this->createAccessDeniedException();
    }

    // Passer l'entité User directement au repository (plus propre)
    $scores = $scoreRepository->findByUserOrdered($user);
    $bestScore = $scoreRepository->getBestScore($user);
    $averageScore = $scoreRepository->getAverageScore($user);

    return $this->render('score/index.html.twig', [
        'scores' => $scores,
        'bestScore' => $bestScore,
        'averageScore' => $averageScore,
        'totalScores' => count($scores),
    ]);
}

/**
 * @Route("/save", name="app_score_save", methods={"POST"})
 */
public function save(
    Request $request,
    EntityManagerInterface $entityManager,
    ScoreRepository $scoreRepository
): Response {
    $user = $this->getUser();

    if (!$user instanceof User) {
        return $this->json(['error' => 'Utilisateur non connecté.'], Response::HTTP_FORBIDDEN);
    }

    $value = $request->getPayload()->getInt('value');

    if ($value < 0) {
        return $this->json(['error' => 'Le score ne peut pas être négatif.'], Response::HTTP_BAD_REQUEST);
    }

    $score = new Score();
    $score->setUser($user); // Entité User directement
    $score->setValue($value);

    $entityManager->persist($score);
    $entityManager->flush();

    // Passer l'entité User directement
    $bestScore = $scoreRepository->getBestScore($user);
    $averageScore = $scoreRepository->getAverageScore($user);

    return $this->json([
        'success' => true,
        'message' => 'Score enregistré!',
        'score' => $value,
        'bestScore' => $bestScore,
        'averageScore' => round($averageScore ?? 0, 2),
    ]);
}

    #[Route('/{id}/delete', name: 'app_score_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Score $score,
        EntityManagerInterface $entityManager
    ): Response {
        // Check if user owns this score
        if ($score->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous n\'avez pas accès à cette ressource.');
        }

        if ($this->isCsrfTokenValid('delete' . $score->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($score);
            $entityManager->flush();

            $this->addFlash('success', 'Score supprimé!');
        }

        return $this->redirectToRoute('app_score_index');
    }
}
