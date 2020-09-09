<?php

namespace App\Controller;

use App\Repository\ArticleConcurrentRepository;
use App\Repository\ArticleRepository;
use App\Repository\ArticleVendeurRepository;
use App\Repository\ConcurrentRepository;
use App\Repository\EtatRepository;
use App\Service\VendeurService;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\ORMException;
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

    private $manager;
    
    private const ETATS_CALCUL = [
        1 => 'Etat moyen',
        2 => 'Bon état',
        3 => 'Très bon état',
        4 => 'Comme neuf',
        5 => 'Neuf'
    ];

    public function __construct(ArticleRepository $articleRepository,
                                EtatRepository $etatRepository, 
                                ConcurrentRepository $concurrentRepository,
                                ArticleConcurrentRepository $articleConcurrentRepository,
                                ArticleVendeurRepository $articleVendeurRepository,
                                ObjectManager $manager) 
    {
        $this->articleRepository = $articleRepository;
        $this->etatRepository = $etatRepository;
        $this->concurrentRepository = $concurrentRepository;
        $this->articleConcurrentRepository = $articleConcurrentRepository;
        $this->articleVendeurRepository = $articleVendeurRepository;
        $this->manager = $manager;;
    }

    /**
     * @Route("/concurrents", name="concurrents_list")
     */
    public function index()
    {      
        $all_concurrents = $this->concurrentRepository->findBy([], ['id' => 'DESC']);   
        
        return $this->render('concurrent/index.html.twig', [
            'all_concurrents' => $all_concurrents,
        ]);
    }


    /**
     * @Route("/offres/article/{id_article}/etat/{id_etat}", name="concurrents_list_etat_get")
     */
    public function indexEtatGet($id_article, $id_etat)
    {
        $idsEtats = array_keys(self::ETATS_CALCUL);
        if (!in_array($id_etat, $idsEtats) || !is_numeric($id_article) ) {
            $this->addFlash('danger', 'Oups, l\'adresse demandée n\'est pas correcte !');            
            return $this->redirect($this->generateUrl('index'));
        }

        // Article
        $article = $this->articleRepository->find($id_article);

        // Articles
        $articles = $this->articleRepository->findAll();


        // Etat
        $etatSelectionne = self::ETATS_CALCUL[$id_etat];
        $etatObjet = $this->etatRepository->findBy(
            ['intitule' => $etatSelectionne]
        );

        // Tous les états
        $tous_etats = $this->etatRepository->findAll();

        // Concurrents pour cet Article et cet Etat (prix asc : meilleure offre)
        $concurrentsArticleEtat = $this->articleConcurrentRepository->findBy([
            'etat' => $etatObjet,
            'article' => $article
        ], [
            'prix' => 'ASC'
        ]);

        return $this->render('concurrent/meilleurs_offres.html.twig', [
            'concurrentsArticleEtat' => $concurrentsArticleEtat,
            'tous_articles' => $articles,
            'article' => $article,
            'etatSelectionne' => $id_etat,
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
        $id_article = $request->get('articleChoisi');
        return $this->redirect($this->generateUrl('concurrents_list_etat_get', ['id_etat' => $id_etat, 'id_article' => $id_article] ));
    }

    /**
     * @Route("/article/{id}/concurrencer/{etatSelectionne}", name="placer_concurrence_form")
     */
    public function placerForm($id, $etatSelectionne)
    {
        if (is_numeric($id) && $id > 0 && 
            is_numeric($etatSelectionne) && in_array($etatSelectionne, array_keys(self::ETATS_CALCUL))) {

            // Get Article
            $article = $this->articleRepository->find($id);


            // Récupérer les états
            $etats = $this->etatRepository->findAll();            

            return $this->render('concurrent/placer.html.twig', [
                'controller_name' => 'ConcurrentController',
                'etats' => $etats,
                'etats_fixes' => self::ETATS_CALCUL,
                'etatSelectionne' => $etatSelectionne,
                'article' => $article
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
            [
                'etat' => $etat,
                'article' => $article
            ]
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

        // Etats supérieurs existent ( <> 'Neuf' )
        if (count($intituleEtat)) {        
            $etats = $this->etatRepository->findByIntitules($intituleEtat);            
            $articlesEtatsDifferents = $this->articleConcurrentRepository->getArticlesConcurrentsWithEtats($etats, $article);
            foreach($articlesEtatsDifferents as $ac) {
                $prixMeilleurEtat[$ac->getEtat()->getIntitule()][] = $ac->getPrix();
            }
        }

        // Vérifier que le prix de l'article n'a pas été fixé auparavent pour cet état         
        $articleAvecPrix = $this->articleVendeurRepository->findOneBy(
            ['article' => $article, 'etat' => $etat]
        );

        if (!$articleAvecPrix) {

            // Si le prix du produit n'existe pas chez le concurrent dans aucun état
            if (!count($prixMemeEtat) && !count($prixMeilleurEtat)) {
                $this->addFlash('warning', 'Impossible de placer un prix pour cet article, car aucun concurrent ne l\'a fait auparavant !');
            } else {

                // Service 
                $venderuService  = new VendeurService($this->getDoctrine()->getManager());

                $prix_de_vente = $venderuService->calculerPrix($article, $etat, $prixPlancher, $prixMemeEtat, $prixMeilleurEtat);

                if ($prix_de_vente == 0) {
                    $this->addFlash('warning', 'Le prix n\'a pu être fixé pour cet article. Veuillez vérifier votre prix plancher !');
                } else {
                    $this->addFlash('success', 'L\'article <b>'.$article->getLibelle().'</b> dont l\'état est <b>'.$etatForm.'</b> a été positionné au prix de <b>'.number_format($prix_de_vente, 2).' €</b>.');
                }
            }

        } else {
            $this->addFlash('warning', 'Attention, le prix a déjà été fixé pour cet article avec cet état.');
        }

        // Si la requete n'est pas bonne
        return $this->redirect($this->generateUrl('articles_vendeur_list'));
    }

    /**
     * @Route("/concurrents/update", name="concurrent_update")
     */
    public function update(Request $request)
    {
        // Infos concurrent 
        $villeConcurrent = $request->get('villeConcurrent');
        $telConcurrent = $request->get('telConcurrent');
        $idConcurrent = $request->get('idConcurrent');        

        $entity = $this->concurrentRepository->findOneBy(
            ['id' => $idConcurrent]
        );

        // Update data
        $entity->setVille($villeConcurrent);
        $entity->setTel($telConcurrent);

        // Update de l'entité 
        try {
            $this->manager->persist($entity);
            $this->manager->flush();
        } catch(ORMException $ex) {
            
        }

        // Todo : Le prix a déjà été fixé pour cet article
        $this->addFlash('success', 'Les informations du concurrent <b>'.$entity->getNom().'</b> ont été mises à jour.');        

        // Si la requete n'est pas bonne
        return $this->redirect($this->generateUrl('concurrents_list'));
    }
}
