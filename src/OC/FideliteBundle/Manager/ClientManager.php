<?php

namespace OC\FideliteBundle\Manager;

use Doctrine\ORM\EntityManager;
use OC\FideliteBundle\Entity\Client;
use OC\FideliteBundle\Form\Type\ClientSearchType;
use OC\FideliteBundle\Form\Type\ClientType;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\RequestStack;

class ClientManager {

    /**
     * @var Container
     */
    private $container;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var FormFactory
     */
    private $form;

    /**
     * @var RequestStack
     */
    private $request;

    /**
     * Client Manager constructor.
     * @param EntityManager $em
     * @param FormFactory $form
     * @param RequestStack $request
     * @param Container $container
     */
    public function __construct(EntityManager $em, FormFactory $form, RequestStack $request, Container $container)
    {
        $this->em = $em;
        $this->form = $form;
        $this->request = $request;
        $this->container = $container;
    }

    /**
     * Insertion d'un nouveau client en BDD
     * @return \Symfony\Component\Form\FormInterface
     */
    public function add() {
        $request = $this->request->getCurrentRequest();

        $client = new Client();

        $form = $this->form->create(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($client);
            $this->em->flush($client);
        }
        return $form;
    }

    public function read($id) {
        return $this->em->getRepository('OCFideliteBundle:Client')->findOneBy(array('id' => $id));
    }

    public function readAll() {
        $clients = $this->em->getRepository('OCFideliteBundle:Client')->findAll();
        $clients = $this->em->getRepository('OCFideliteBundle:Client')->getAllClientsParOrdre($clients);
        return $clients;
    }

    public function update($client) {
        $request = $this->request->getCurrentRequest();

        $editForm = $this->form->create(ClientType::class, $client);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->em->flush();
        }
        return $editForm;
    }

    public function delete($client) {
        $client = $this->em->getRepository('OCFideliteBundle:Client')->find($client);
        $nbrVentes = $client->getNbrventes();
        $ventes = $this->em->getRepository('OCFideliteBundle:Vente')->getAllVentesByClient($client);
        if ($nbrVentes == 0) {
            $this->em->remove($client);
            $this->em->flush($client);
            $this->container->get('ras_flash_alert.alert_reporter')->addError("Fiche Client supprimée !");
        } else {
            $this->container->get('ras_flash_alert.alert_reporter')->addError("Suppression impossible car le client a des ventes affectées !");
        }
    }

    public function recap($client) {
        $request = $this->request->getCurrentRequest();
        $form = $this->form->create(ClientSearchType::class, $client);
        $form->handleRequest($request);
        return $form;
    }

    public function cadeauAnniv($client) {

        $today = new \DateTime();
        $clients = $this->em->getRepository('OCFideliteBundle:Client')->findAll();
        foreach ($clients as $client) {
            $anniv = $client->getDateNaissance();
            if ($today == $anniv) {
                return $this->get('oc_fidelitebundle.email')->envoiMail($client);
            }
        }
    }
}
