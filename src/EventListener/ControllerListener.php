<?php 

namespace App\EventListener;

use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\Security\Core\Security;
use Twig\Environment;

class ControllerListener {

    private $twig;
    private $articleRepository;

    public function __construct( Environment $twig, ArticleRepository $articleRepository ) {
        $this->twig = $twig;
        $this->articleRepository = $articleRepository;
    }

    public function onKernelController( FilterControllerEvent $event ): void {

        // Get article by default
        $article_default = $this->articleRepository->findBy([], null, 1);

        if (count($article_default)) {
            $this->twig->addGlobal( 'id_article_default', $article_default[0]->getId() );
        }
    }
}