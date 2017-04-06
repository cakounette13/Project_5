<?php

namespace OC\FideliteBundle\Controller;

use OC\FideliteBundle\Entity\Client;
use OC\FideliteBundle\EventManager\BirthdayEvent;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller {

    /**
     * Page d'accueil
     *
     * @Route("/", name="accueil")
     *
     */
    public function indexAction()
    {
//        $clients = $this->get('oc_fidelite.client_manager')->readAll();
//
//        foreach ($clients as $client) {
//            var_dump($client);
//            $event = new BirthdayEvent($client);
//            $this->get('event_dispatcher')->dispatch(BirthdayEvent::NAME, $event);
//        }
        return $this->render('base.html.twig');
    }
}
