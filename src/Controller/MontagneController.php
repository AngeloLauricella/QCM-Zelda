<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/montagne', name: 'montagne_')]
#[IsGranted('ROLE_USER')]
class MontagneController extends AbstractController
{
    #[Route('', name: 'montagne')]
    public function montagne(): Response
    {
        return $this->redirectToRoute('game_start');    }
}