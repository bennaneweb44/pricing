<?php

namespace App\Controller;

use App\Repository\ArticleVendeurRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ArticleVendeurController extends AbstractController
{
    private $articleVendeurRepository;
    private $manager;

    public function __construct(ArticleVendeurRepository $articleVendeurRepository, ObjectManager $manager) 
    {
        $this->articleVendeurRepository = $articleVendeurRepository;
        $this->manager = $manager;
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

    /**
     * @Route("/articles/vendeur/delete", name="articles_vendeur_delete")
     */
    public function delete(Request $request)
    {
        // get Id 
        $idArticleVendeur = $request->get('idArticleVendeur');

        $entity = $this->articleVendeurRepository->findOneBy(
            ['id' => $idArticleVendeur]
        );

        // Suppression de l'entité de la base
        try {
            $this->manager->remove($entity);
            $this->manager->flush();
        } catch(ORMException $ex) {
            
        }

        // Todo : Le prix a déjà été fixé pour cet article
        $this->addFlash('error', 'Prix de concurrence supprmé pour cet article.');        

        // Si la requete n'est pas bonne
        return $this->redirect($this->generateUrl('articles_vendeur_list'));
    }
}
