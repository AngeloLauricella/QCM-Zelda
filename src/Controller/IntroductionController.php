<?php

namespace App\Controller;

use App\Service\PlayerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/introduction', name: 'introduction_')]
#[IsGranted('ROLE_USER')]
class IntroductionController extends AbstractController
{
    // Questions avec texte et réponse
    private array $questions = [
        1 => ['text' => "Comment s'appelle l'ennemi poursuivi ?", 'answer' => "poursuivie"],
        2 => ['text' => "Quel est le mot magique ?", 'answer' => "mojo"],
        3 => ['text' => "Comment s'appelle le compagnon ?", 'answer' => "Navi"],
        4 => ['text' => "Quelle tribu habite la montagne ?", 'answer' => "Gorons"],
        5 => ['text' => "Quelle est la tribu secrète ?", 'answer' => "Sheikah"],
        6 => ['text' => "Quel objet magique est utilisé ?", 'answer' => "Mirroir"],
        7 => ['text' => "Qui est le grand antagoniste ?", 'answer' => "Ganondorf"],
    ];

    #[Route('/{step}', name: 'step')]
    public function step(
        int $step,
        Request $request,
        PlayerService $playerService,
        EntityManagerInterface $em
    ): Response {
        $user = $this->getUser();
        $player = $playerService->getOrCreatePlayerForUser($user);

        // Game over
        if ($player->isGameOver()) {
            return $this->redirectToRoute('game_over');
        }

        // Vérifie que la question existe
        if (!isset($this->questions[$step])) {
            // Si on dépasse le nombre de questions, on termine l'introduction
            return $this->redirectToRoute('game_index');
        }

        $question = $this->questions[$step];
        $resultDisplayed = false;
        $isCorrect = false;
        $scoreChange = 0;
        $userAnswer = null;

        // POST = le joueur a répondu
        if ($request->isMethod('POST') && $request->request->has('answer')) {
            $userAnswer = trim($request->request->get('answer'));
            $isCorrect = strtolower($userAnswer) === strtolower($question['answer']);
            $scoreChange = $isCorrect ? 3 : -1;

            // Mise à jour du score
            $player->setScore(max(0, $player->getScore() + $scoreChange));
            $player->setLastStep($step); // sauvegarde progression
            $em->flush(); // sauvegarde en DB

            $resultDisplayed = true;

            // Vérifie si game over
            if ($player->getScore() <= 0) {
                return $this->redirectToRoute('game_over');
            }

            // Si correct et il reste des questions, passer à la suivante automatiquement
            if ($isCorrect && isset($this->questions[$step + 1])) {
                return $this->redirectToRoute('introduction_step', ['step' => $step + 1]);
            }
        }

        // Calcul des cœurs
        $hearts = (int) floor($player->getScore() / 20);

        return $this->render("introduction/intro{$step}.html.twig", [
            'player' => $player,
            'questionText' => $question['text'],
            'result_displayed' => $resultDisplayed,
            'user_answer' => $userAnswer,
            'is_correct' => $isCorrect,
            'score_change' => $scoreChange,
            'score' => $player->getScore(),
            'hearts' => $hearts,
            'step' => $step,
            'next_step' => $step + 1,
        ]);
    }
}
