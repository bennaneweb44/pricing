<?php

namespace App\Controller;

use App\Repository\ArticleVendeurRepository;
use App\Repository\ConcurrentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends AbstractController
{
    private $articleVendeurRepository;
    private $concurrentRepository;

    public function __construct(ArticleVendeurRepository $articleVendeurRepository, ConcurrentRepository $concurrentRepository) 
    {
        $this->articleVendeurRepository = $articleVendeurRepository;
        $this->concurrentRepository = $concurrentRepository;
    }
    
    public function index(): Response
    {
        if (!$this->getUser()) {
            return $this->redirect($this->generateUrl('app_login'));
        }

        // Récupérer les 5 derniers articles concurrencés
        $articlesVendeurs = $this->articleVendeurRepository->findBy([], ['id' => 'DESC'], 5);        


        // Réupérer les 5 derniers concurrents enregistrés
        $allConcurrents = $this->concurrentRepository->findBy([], ['id' => 'DESC'], 5);        
        
        return $this->render('default/index.html.twig', [
            'articlesVendeurs' => $articlesVendeurs,
            'allConcurrents' => $allConcurrents
        ]);
    }
}