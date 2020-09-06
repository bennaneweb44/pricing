<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ConcurrentController extends AbstractController
{
    /**
     * @Route("/concurrent", name="concurrent")
     */
    public function index()
    {
        return $this->render('concurrent/index.html.twig', [
            'controller_name' => 'ConcurrentController',
        ]);
    }
}
