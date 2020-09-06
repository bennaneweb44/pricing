<?php

namespace App\Controller;

use App\Repository\ArticleConcurrentRepository;
use App\Repository\ArticleRepository;
use App\Repository\ArticleVendeurRepository;
use App\Repository\EtatRepository;
use App\Service\VendeurService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ConcurrentController extends AbstractController
{
    private $articleRepository;
    private $etatRepository;
    private $articleConcurrentRepository;
    private $articleVendeurRepository;    
    
    private const ETATS_CALCUL = [
        1 => 'Etat moyen',
        2 => 'Bon état',
        3 => 'Très bon état',
        4 => 'Comme neuf',
        5 => 'Neuf'
    ];

    public function __construct(ArticleRepository $articleRepository,
                                EtatRepository $etatRepository, 
                                ArticleConcurrentRepository $articleConcurrentRepository,
                                ArticleVendeurRepository $articleVendeurRepository) 
    {
        $this->articleRepository = $articleRepository;
        $this->etatRepository = $etatRepository;
        $this->articleConcurrentRepository = $articleConcurrentRepository;
        $this->articleVendeurRepository = $articleVendeurRepository;
    }

    /**
     * @Route("/concurrents", name="concurrents_list")
     */
    public function index()
    {
        return $this->render('concurrent/index.html.twig', [
            'controller_name' => 'ConcurrentController',
        ]);
    }

    /**
     * @Route("/article/{id}/concurrencer", name="placer_concurrence_form")
     */
    public function placerForm($id)
    {        
        if (is_numeric($id) && $id > 0) {

            // Récupérer les états
            $etats = $this->etatRepository->findAll();            

            return $this->render('concurrent/placer.html.twig', [
                'controller_name' => 'ConcurrentController',
                'etats' => $etats,
                'id_article' => $id
            ]);    
        }

        // Si la requete n'est pas bonne
        return $this->render('default/index.html.twig', []);
    }

    /**
     * @Route("/article/concurrencer/action", name="placer_concurrence_action")
     */
    public function placerAction(Request $request)
    {
        // Elements postés        
        $idArticle = $request->get('id_article');
        $prixPlancher = $request->get('prix_plancher');
        $etatForm = $request->get('selectEtats');

        // Get Article
        $article = $this->articleRepository->find($idArticle);

        // Get Etat
        $etat = $this->etatRepository->findOneBy(
            ['intitule' => $etatForm]
        );

        ///////////////////////// Concurrents du MEME état /////////////////////////
        $articlesMemeEtat = $this->articleConcurrentRepository->findBy(
            [ 'etat' => $etat]
        );

        $prixMemeEtat = [];
        foreach($articlesMemeEtat as $ac) {
            if (!in_array($ac->getPrix(), $prixMemeEtat)) {
                $prixMemeEtat[] = $ac->getPrix();
            }
        }

        /////////////////////// Concurrents avec MEILLEUR état /////////////////////
        $prixMeilleurEtat = [];

        $intituleEtat = '';          
        $indice = 0;
        foreach(self::ETATS_CALCUL as $cle => $valeur) {
            if ($valeur == $etatForm) {
                $indice = $cle;
            } elseif ($indice > 0) {
                $intituleEtat = $valeur;
                break;
            }
        }

        // Get Etat
        $etatMeilleur = $this->etatRepository->findOneBy(
            ['intitule' => $intituleEtat]
        );

        $articlesMemeEtat = $this->articleConcurrentRepository->findBy(
            [ 'etat' => $etatMeilleur]
        );

        foreach($articlesMemeEtat as $ac) {
            if (!in_array($ac->getPrix(), $prixMeilleurEtat)) {
                $prixMeilleurEtat[] = $ac->getPrix();
            }
        }

        // Vérifier que le prix de l'article n'a pas été fixé auparavent pour cet état         
        $articleAvecPrix = $this->articleVendeurRepository->findOneBy(
            ['article' => $article, 'etat' => $etat]
        );

        if (!$articleAvecPrix) {

            // Service 
            $venderuService  = new VendeurService($this->getDoctrine()->getManager());
            $prix_de_vente = $venderuService->calculerPrix($article, $etat, $prixPlancher, $prixMemeEtat, $prixMeilleurEtat);

            if ($prix_de_vente > 0) {
                // Todo : Envoyer message POSITIF à la vue
            } else {
                // Todo : Envoyer message NEGATIF à la vue
            }
        }

        // Si la requete n'est pas bonne
        return $this->redirect($this->generateUrl('articles_list'));
    }
}
