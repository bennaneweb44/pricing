<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends AbstractController
{
    public function index(): Response
    {
        if (!$this->getUser()) {
            return $this->redirect($this->generateUrl('app_login'));
        }
        
        return $this->render('default/index.html.twig', []);
    }
}