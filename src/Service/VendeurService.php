<?php

namespace App\Service;

use stdClass;

class VendeurService
{
    private $vendeur;

    public function __construct()
    {
        $this->vendeur = new stdClass();
        
        $this->vendeur->nom = 'BWV';
        $this->vendeur->ville = 'Sainte-luce-sur-loire';
        $this->vendeur->tel = '07.60.71.57.58';
        $this->vendeur->email = 'bennaneweb44@gmail.com';
    }

    public function getVendeur()
    {
        return $this->vendeur;
    }
}