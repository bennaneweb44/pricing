<?php

namespace App\Service;

use Doctrine\ORM\EntityManager;
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
     * Cacul selon stratégie :
     * 
     */
    public function calculerPrix($prixPlancher, $prixMemeEtat = [], $prixEtatSuperieur = []) {

        $retour = 0;

        if (count($prixMemeEtat)) {

        } elseif ($prixEtatSuperieur) {

        }

        return $retour;
    }
}