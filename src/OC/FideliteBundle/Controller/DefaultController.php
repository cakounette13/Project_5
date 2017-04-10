<?php

namespace OC\FideliteBundle\Controller;

use OC\FideliteBundle\EventManager\BirthdayEvent;
use OC\FideliteBundle\EventManager\BirthdayEventListener;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Validator\Constraints\DateTime;

class DefaultController extends Controller {

    /**
     * Page d'accueil
     *
     * @Route("/", name="accueil")
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $clients = $em->getRepository('OCFideliteBundle:Client')->findAll();

        foreach ($clients as $client) {
            $mailEnvoyeLe = $client->getMailEnvoyeLe()->format('d m');
            $todayEntier = new \DateTime();
            $today = date('d m');

            if ($mailEnvoyeLe != $today) {
                $event = new BirthdayEvent($client);
                $this->get('event_dispatcher')->dispatch(BirthdayEvent::NAME, $event);
                $client->setMailEnvoyeLe($todayEntier);
                $em->flush($client);
            }
       }
        return $this->render('base.html.twig');
    }
}
