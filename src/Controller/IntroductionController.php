<?php

namespace App\Controller;

use App\Service\PlayerService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/introduction', name: 'introduction_')]
#[IsGranted('ROLE_USER')]
class IntroductionController extends AbstractController
{
    private array $questions = [
        1 => ['text' => "Comment s'appelle l'ennemi poursuivi ?"],
        2 => ['text' => "Quel est le mot magique ?"],
        3 => ['text' => "Comment s'appelle le compagnon ?"],
        4 => ['text' => "Quelle tribu habite la montagne ?"],
        5 => ['text' => "Quelle est la tribu secrète ?"],
        6 => ['text' => "Quel objet magique est utilisé ?"],
        7 => ['text' => "Qui est le grand antagoniste ?"],
    ];

    #[Route('/{step}', name: 'step')]
    public function step(
        int $step,
        PlayerService $playerService
    ): Response {
        $user = $this->getUser();
        $player = $playerService->getOrCreatePlayerForUser($user);

        if (!isset($this->questions[$step])) {
            return $this->redirectToRoute('game_start');
        }

        $question = $this->questions[$step];

        return $this->render("introduction/intro{$step}.html.twig", [
            'player' => $player,
            'questionText' => $question['text'],            'step' => $step,
            'next_step' => $step + 1,
        ]);
    }
}