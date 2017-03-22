<?php

namespace OC\FideliteBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller {

    /**
     * @Route("/", name="accueil")
     *
     */
    public function indexAction()
    {
        return $this->render('base.html.twig');
    }
}

