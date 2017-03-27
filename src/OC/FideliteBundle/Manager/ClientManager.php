<?php

namespace OC\FideliteBundle\Manager;

use Doctrine\ORM\EntityManager;
use OC\FideliteBundle\Entity\Client;
use OC\FideliteBundle\Form\Type\ClientSearchType;
use OC\FideliteBundle\Form\Type\ClientType;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\RequestStack;

class ClientManager {
    /**
     * @var ContainerInterface
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
     * @param ContainerInterface $container
     * @param FormFactory $form
     * @param RequestStack $request
     */
    public function __construct(EntityManager $em, ContainerInterface $container, FormFactory $form, RequestStack $request)
    {
        $this->em = $em;
        $this->container = $container;
        $this->form = $form;
        $this->request = $request;
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
        $this->em->remove($client);
        $this->em->flush($client);
    }

    public function recap($client) {
        $request = $this->request->getCurrentRequest();

        $form = $this->form->create(ClientSearchType::class, $client);
        $form->handleRequest($request);
        return $form;
    }
}
