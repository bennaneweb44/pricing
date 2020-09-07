<?php

namespace App\Service;

use App\Entity\Article;
use App\Entity\ArticleVendeur;
use App\Entity\Etat;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;
use stdClass;

class VendeurService
{
    private $vendeur;

    public function __construct(EntityManager $em)
    {
        $this->vendeur = new stdClass();
        
        $this->vendeur->nom = 'BWV';
        $this->vendeur->ville = 'Sainte-luce-sur-loire';
        $this->vendeur->tel = '07.60.71.57.58';
        $this->vendeur->email = 'bennaneweb44@gmail.com';

        $this->em = $em;
    }

    public function getVendeur()
    {
        return $this->vendeur;
    }

    /**
     * Cacul selon stratégie
     */
    public function calculerPrix(Article $article, Etat $etat, $prixPlancher, $prixMemeEtat = [], $prixEtatSuperieur = []) 
    {
        $prixDeVente = 0;

        // Tri des tableaux des prix pour viser le moins cher des concurrents                
        sort($prixMemeEtat);
        sort($prixEtatSuperieur);

        foreach($prixMemeEtat as $prix_m) {
            if ($prixPlancher <= floatval($prix_m) - 0.01 ) {                
                $prixDeVente = floatval($prix_m) - 0.01;
                break;
            }   
        }        
        
        if ($prixDeVente == 0) {
            foreach($prixEtatSuperieur as $prix_s) {
                if ($prixPlancher <= floatval($prix_s) - 1) {
                    $prixDeVente = floatval($prix_s) - 1;
                    break;
                }   
            }
        }

        if ($prixDeVente > 0) {

            // Enregistrer Article Vendeur
            $articleVendeur = new ArticleVendeur();
            $articleVendeur->setPlancher($prixPlancher);
            $articleVendeur->setPrix($prixDeVente);
            $articleVendeur->setArticle($article);
            $articleVendeur->setEtat($etat);

            try {
                $this->em->persist($articleVendeur);
                $this->em->flush();
            } catch(ORMException $ex) {
                
            }
        }

        return $prixDeVente;
    }
}