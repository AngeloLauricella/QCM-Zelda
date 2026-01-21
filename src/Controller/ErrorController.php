<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Contrôleur de gestion des erreurs personnalisées
 */
class ErrorController extends AbstractController
{
    /**
     * Affiche la page d'erreur 404
     */
    #[Route('/error/404', name: 'error_404')]
    public function error404(?FlattenException $exception = null): Response
    {
        return $this->render('bundles/TwigBundle/Exception/error404.html.twig', [
            'exception' => $exception,
        ]);
    }

    /**
     * Affiche la page d'erreur 500
     */
    #[Route('/error/500', name: 'error_500')]
    public function error500(?FlattenException $exception = null): Response
    {
        return $this->render('bundles/TwigBundle/Exception/error500.html.twig', [
            'exception' => $exception,
        ]);
    }

    /**
     * Affiche la page d'erreur générique
     */
    #[Route('/error/{code}', name: 'error_generic')]
    public function errorGeneric(int $code = 500, ?FlattenException $exception = null): Response
    {
        return $this->render('bundles/TwigBundle/Exception/error.html.twig', [
            'code' => $code,
            'exception' => $exception,
        ]);
    }
}
