<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class CsrfTestController extends AbstractController
{
    #[Route('/csrf-test', name: 'csrf_test')]
    public function index(Request $request, CsrfTokenManagerInterface $csrfTokenManager): Response
    {
        $message = null;

        // Vérifier le POST
        if ($request->isMethod('POST')) {
            $submittedToken = $request->request->get('_csrf_token');

            if ($csrfTokenManager->isTokenValid(new \Symfony\Component\Security\Csrf\CsrfToken('form_test', $submittedToken))) {
                $message = 'Token valide ✅';
            } else {
                $message = 'Token invalide ❌';
            }
        }

        return $this->render('csrf_test/index.html.twig', [
            'message' => $message,
        ]);
    }
}
