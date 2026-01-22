<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/question', name: 'question_')]
#[IsGranted('ROLE_USER')]
class QuestionController extends AbstractController
{
    #[Route('/{category}', name: 'show')]
    public function show(string $category): Response
    {
        return $this->redirectToRoute('game_start');
    }

    #[Route('/{category}/answer', name: 'answer', methods: ['POST'])]
    public function answer(string $category): Response
    {
        return $this->redirectToRoute('game_start');
    }

    #[Route('/gameover', name: 'gameover')]
    public function gameOver(): Response
    {
        return $this->redirectToRoute('game_over');    }
}