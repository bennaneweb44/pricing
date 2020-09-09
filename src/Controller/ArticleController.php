<?php

namespace App\Controller;

use App\Repository\ArticleConcurrentRepository;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{   
    private $articleRepository;

    public function __construct(ArticleRepository $articleRepository) 
    {
        $this->articleRepository = $articleRepository;
    }

    /**
     * @Route("/articles", name="articles")
     */
    public function index()
    {

        // Autoriser la concurrence que sur 2 articles
        $tous_les_articles = $this->articleRepository->findAll();       

        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
            'articles' => $tous_les_articles
        ]);
    }
}
