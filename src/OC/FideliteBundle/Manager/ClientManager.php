<?php

namespace OC\FideliteBundle\Manager;

use Doctrine\ORM\EntityManager;
use OC\FideliteBundle\Entity\Client;
use OC\FideliteBundle\Form\Type\ClientSearchType;
use OC\FideliteBundle\Form\Type\ClientType;
use OC\FideliteBundle\Services\Email;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;

class ClientManager
{
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
     * @var Session
     */
    private $session;

    /**
     * ClientManager constructor.
     *
     * @param EntityManager $em
     * @param FormFactory $form
     * @param RequestStack $request
     * @param Session $session
     */
    public function __construct(EntityManager $em, FormFactory $form, RequestStack $request, Session $session)
    {
        $this->em = $em;
        $this->form = $form;
        $this->request = $request;
        $this->session = $session;
    }

    /**
     * Insertion d'un nouveau client en BDD
     * @return \Symfony\Component\Form\FormInterface
     */
    public function add() {
        $request = $this->request->getCurrentRequest();

        $client = new Client();
        $date = new \DateTime();
        $client->setMailEnvoyeLe($date);
        $form = $this->form->create(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($client);
            $this->em->flush($client);
            $this->session->getFlashBag()->add('success', "Nouveau Client créé !");
        }
        return $form;
    }

    /**
     * Voir un client avec son id
     *
     * @param $id
     * @return null|object|Client
     */
    public function read($id) {
        return $this->em->getRepository('OCFideliteBundle:Client')->findOneBy(array('id' => $id));
    }

    /**
     * Voir tous les clients
     *
     * @return array|int|Client[]
     */
    public function readAll() {
        $clients = $this->em->getRepository('OCFideliteBundle:Client')->getAllClientsParOrdre();

        return $clients;
    }

    /**
     * Modifier un client
     *
     * @param $client
     * @return \Symfony\Component\Form\FormInterface
     */
    public function update($client) {
        $request = $this->request->getCurrentRequest();

        $editForm = $this->form->create(ClientType::class, $client);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->em->flush();
            $this->session->getFlashBag()->add('success', "Fiche Client modifiée !");
        }
        return $editForm;
    }

    /**
     * Supprimer un client
     *
     * @param $client
     */
    public function delete($client) {
        $client = $this->em->getRepository('OCFideliteBundle:Client')->find($client);
        $nbrVentes = $client->getNbrventes();
        if ($nbrVentes == 0) {
            $this->em->remove($client);
            $this->em->flush($client);
            $this->session->getFlashBag()->add('danger', 'Fiche Client supprimée !');
        } else {
            $this->session->getFlashBag()->add('danger', 'Suppression impossible car le client a des ventes affectées !');
        }
    }

    /**
     * Liste déroulante avec tous les clients
     *
     * @param $client
     * @return \Symfony\Component\Form\FormInterface
     */
    public function recap($client) {
        $request = $this->request->getCurrentRequest();
        $form = $this->form->create(ClientSearchType::class, $client);
        $form->handleRequest($request);
        return $form;
    }
}
