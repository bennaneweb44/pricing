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
        // Récupérer l'article d'exemple : Fifa 20 édition Ultimate
        $articleExemple = $this->articleRepository->findOneBy(
            ['reference' => 'JVD-105057'],
        );

        // Autoriser la concurrence que sur cet article
        $tous_les_articles = $this->articleRepository->findAll();
        foreach($tous_les_articles as $article) {
            $article->concurrence = 0;
            if ($article->getReference() == 'JVD-105057') {
                $article->concurrence = 1;
            }
        }        

        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
            'articles' => $this->articleRepository->findAll(),
            'article_exemple' => $articleExemple 
        ]);
    }
}
