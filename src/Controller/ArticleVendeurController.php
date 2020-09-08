<?php

namespace App\Controller;

use App\Repository\ArticleVendeurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ArticleVendeurController extends AbstractController
{
    private $articleVendeurRepository;

    public function __construct(ArticleVendeurRepository $articleVendeurRepository) 
    {
        $this->articleVendeurRepository = $articleVendeurRepository;
    }

    /**
     * @Route("/articles/vendeur", name="articles_vendeur_list")
     */
    public function index()
    {
        // Récupérer Tous les articles concurrencés
        $articlesVendeurs = $this->articleVendeurRepository->findBy([], ['id' => 'DESC']);

        return $this->render('articles_vendeur/index.html.twig', [
            'controller_name' => 'ArticleVendeurController',
            'articlesVendeurs' => $articlesVendeurs
        ]);
    }
}
