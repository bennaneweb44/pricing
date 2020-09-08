<?php

namespace App\Controller;

use App\Repository\ArticleVendeurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends AbstractController
{
    private $articleVendeurRepository;

    public function __construct(ArticleVendeurRepository $articleVendeurRepository) 
    {
        $this->articleVendeurRepository = $articleVendeurRepository;
    }
    
    public function index(): Response
    {
        if (!$this->getUser()) {
            return $this->redirect($this->generateUrl('app_login'));
        }

        // Récupérer Tous les articles concurrencés
        $articlesVendeurs = $this->articleVendeurRepository->findAll();
        
        return $this->render('default/index.html.twig', [
            'articlesVendeurs' => $articlesVendeurs
        ]);
    }
}