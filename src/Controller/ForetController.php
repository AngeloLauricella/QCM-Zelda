<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/foret', name: 'foret_')]
#[IsGranted('ROLE_USER')]
class ForetController extends AbstractController
{
    #[Route('/1', name: 'foret1')]
    public function foret1(): Response
    {
        return $this->redirectToRoute('game_start');
    }

    #[Route('/2', name: 'foret2')]
    public function foret2(): Response
    {
        return $this->redirectToRoute('game_start');
    }

    #[Route('/3', name: 'foret3')]
    public function foret3(): Response
    {
        return $this->redirectToRoute('game_start');
    }

    #[Route('/4', name: 'foret4')]
    public function foret4(): Response
    {
        return $this->redirectToRoute('game_start');
    }

    #[Route('/5', name: 'foret5')]
    public function foret5(): Response
    {
        return $this->redirectToRoute('game_start');
    }

    #[Route('/6', name: 'foret6')]
    public function foret6(): Response
    {
        return $this->redirectToRoute('game_start');
    }

    #[Route('/7', name: 'foret7')]
    public function foret7(): Response
    {
        return $this->redirectToRoute('game_start');
    }

    #[Route('/liane', name: 'foret_liane')]
    public function liane(): Response
    {
        return $this->redirectToRoute('game_start');    }
}