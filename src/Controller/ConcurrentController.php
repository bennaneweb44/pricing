<?php

namespace App\Controller;

use App\Repository\ArticleConcurrentRepository;
use App\Repository\ArticleRepository;
use App\Repository\ArticleVendeurRepository;
use App\Repository\ConcurrentRepository;
use App\Repository\EtatRepository;
use App\Service\VendeurService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ConcurrentController extends AbstractController
{
    private $articleRepository;
    private $etatRepository;
    private $concurrentRepository;
    private $articleConcurrentRepository;
    private $articleVendeurRepository;    
    
    private const ETATS_CALCUL = [
        1 => 'Etat moyen',
        2 => 'Bon état',
        3 => 'Très bon état',
        4 => 'Comme neuf',
        5 => 'Neuf'
    ];

    private const REF_ARTICLE_EXEMPLE = 'JVD-105057';

    public function __construct(ArticleRepository $articleRepository,
                                EtatRepository $etatRepository, 
                                ConcurrentRepository $concurrentRepository,
                                ArticleConcurrentRepository $articleConcurrentRepository,
                                ArticleVendeurRepository $articleVendeurRepository) 
    {
        $this->articleRepository = $articleRepository;
        $this->etatRepository = $etatRepository;
        $this->concurrentRepository = $concurrentRepository;
        $this->articleConcurrentRepository = $articleConcurrentRepository;
        $this->articleVendeurRepository = $articleVendeurRepository;
    }

    /**
     * @Route("/concurrents", name="concurrents_list")
     */
    public function index()
    {      
        $all_concurrents = $this->concurrentRepository->findAll();
        
        return $this->render('concurrent/index.html.twig', [
            'all_concurrents' => $all_concurrents,
        ]);
    }


    /**
     * @Route("/concurrents/{etat}", name="concurrents_list_etat_get")
     */
    public function indexEtatGet($etat)
    {
        $int_ids = array_keys(self::ETATS_CALCUL);
        if (!in_array($etat, $int_ids)) {
            $this->addFlash('danger', 'Oups, l\'adresse demandée n\'est pas correcte !');            
            return $this->redirect($this->generateUrl('index'));
        }

        // Article (défaut)
        $article = $this->articleRepository->findOneBy(
            ['reference' => self::REF_ARTICLE_EXEMPLE]
        );

        // Etat (défaut)
        $etatSelectionne = self::ETATS_CALCUL[$etat];
        $etatObjet = $this->etatRepository->findBy(
            ['intitule' => $etatSelectionne]
        );

        // Tous les états
        $tous_etats = $this->etatRepository->findAll();

        // Concurrents pour cet Article et cet Etat (prix : asc)
        $concurrentsArticleEtat = $this->articleConcurrentRepository->findBy([
            'etat' => $etatObjet,
            'article' => $article
        ], [
            'prix' => 'ASC'
        ]);

        return $this->render('concurrent/meilleurs_offres.html.twig', [
            'concurrentsArticleEtat' => $concurrentsArticleEtat,
            'article' => $article,
            'etatSelectionne' => $etat,
            'tous_etats' => $tous_etats,
            'etats_fixes' => self::ETATS_CALCUL
        ]);
    }

    /**
     * Redirection vers la méthode en GET si séléction à partir de liste déroulante
     * 
     * @Route("/concurrents/etat", name="concurrents_list_etat_post")
     */
    public function indexEtatPost(Request $request)
    {
        $id_etat = $request->get('etatChoisi');
        return $this->redirect($this->generateUrl('concurrents_list_etat_get', ['etat' => $id_etat ] ));
    }

    /**
     * @Route("/article/{id}/concurrencer/{etatSelectionne}", name="placer_concurrence_form")
     */
    public function placerForm($id, $etatSelectionne)
    {
        if (is_numeric($id) && $id > 0 && 
            is_numeric($etatSelectionne) && in_array($etatSelectionne, array_keys(self::ETATS_CALCUL))) {

            // Récupérer les états
            $etats = $this->etatRepository->findAll();            

            return $this->render('concurrent/placer.html.twig', [
                'controller_name' => 'ConcurrentController',
                'etats' => $etats,
                'etats_fixes' => self::ETATS_CALCUL,
                'etatSelectionne' => $etatSelectionne,
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

        /////////////////////// Concurrents avec MEILLEURS états ///////////////////
        $prixMeilleurEtat = [];

        $intituleEtat = [];          
        $indice = 0;
        foreach(self::ETATS_CALCUL as $cle => $valeur) {
            if ($valeur == $etatForm) {
                $indice = $cle;                
            } elseif ($indice > 0) {
                $intituleEtat[] = $valeur;
            }
        }

        // Get Etats
        $etats = $this->etatRepository->findByIntitules($intituleEtat);

        $articlesEtatsDifferents = $this->articleConcurrentRepository->getArticlesConcurrentsWithEtats($etats);

        foreach($articlesEtatsDifferents as $ac) {
            $prixMeilleurEtat[$ac->getEtat()->getIntitule()][] = $ac->getPrix();
        }

        // Vérifier que le prix de l'article n'a pas été fixé auparavent pour cet état         
        $articleAvecPrix = $this->articleVendeurRepository->findOneBy(
            ['article' => $article, 'etat' => $etat]
        );

        if (!$articleAvecPrix) {

            // Service 
            $venderuService  = new VendeurService($this->getDoctrine()->getManager());

            $prix_de_vente = $venderuService->calculerPrix($article, $etat, $prixPlancher, $prixMemeEtat, $prixMeilleurEtat);

            if ($prix_de_vente == 0) {
                // Todo : Envoyer message NEGATIF à la vue
                $this->addFlash('warning', 'Le prix n\'a pu être fixé pour cet article. Veuillez vérifier votre prix plancher !');
            } else {
                // Todo : Envoyer message POSITIF à la vue
                $this->addFlash('success', 'L\'article dont l\'état est <b>'.$etatForm.'</b> a été positionné au prix de <b>'.$prix_de_vente.' €</b>.');
            }

        } else {
            // Todo : Le prix a déjà été fixé pour cet article
            $this->addFlash('warning', 'Attention, le prix a déjà été fixé pour cet article avec cet état.');
        }

        // Si la requete n'est pas bonne
        return $this->redirect($this->generateUrl('articles_vendeur_list'));
    }
}
