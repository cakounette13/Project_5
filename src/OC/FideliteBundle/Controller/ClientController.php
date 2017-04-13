<?php

namespace OC\FideliteBundle\Controller;

use OC\FideliteBundle\Entity\Client;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Client controller.
 *
 * @Route("/client")
 */
class ClientController extends Controller
{
    /**
     * Lists all client entities.
     *
     * @Route("/all", name="all_clt")
     * @Method("GET")
     */
    public function allAction()
    {
        $clients = $this->get('oc_fidelite.client_manager')->readAll();

        return $this->render('OCFideliteBundle:Client:all_clt.html.twig', array(
            'clients' => $clients,
        ));
    }

    /**
     * Creates a new client entity.
     *
     * @Route("/new", name="new_clt")
     * @Method({"GET", "POST"})
     */
    public function newAction()
    {
        $form = $this->get('oc_fidelite.client_manager')->add();

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('accueil');
        }

        return $this->render('OCFideliteBundle:Client:new_clt.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a client entity.
     *
     * @Route("/{id}", name="show_clt")
     * @Method("GET")
     */
    public function showAction(Client $client)
    {
        $client = $this->get('oc_fidelite.client_manager')->read($client);

        return $this->render('OCFideliteBundle:Client:show_clt.html.twig', array(
            'client' => $client,
        ));
    }

    /**
     * Displays a form to edit an existing client entity.
     *
     * @Route("/{id}/edit", name="edit_clt")
     * @Method({"GET", "POST"})
     */
    public function editAction(Client $client)
    {
        $editForm = $this->get('oc_fidelite.client_manager')->update($client);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            return $this->redirectToRoute('all_clt', array('id' => $client->getId()));
        }

        return $this->render('OCFideliteBundle:Client:edit_clt.html.twig', array(
            'client' => $client,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Delete a client entity.
     *
     * @Route("/suppr/{id}", name="delete_clt")
     */
    public function deleteAction(Client $client)
    {
        $this->get('oc_fidelite.client_manager')->delete($client);

        return $this->redirectToRoute('all_clt');
    }

    /**
     * Choix du Récapitulatif en sélectionnant le client
     *
     * @Route("/recap/all/", name="recap_clt")
     * @Method({"GET", "POST"})
     */
    public function recapAction()
    {
        $clients = $this->get('oc_fidelite.client_manager')->readAll();
        $form = $this->get('oc_fidelite.client_manager')->recap($clients);

        if ($form->isSubmitted() && $form->isValid()) {
            $id = $form['id']->getData()->getId();

            return $this->redirectToRoute('recap_clt_detail', array(
                'id' => $id
            ));
        }

        return $this->render('OCFideliteBundle:Client:recap_client.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * Récapitulatif d'un client sélectionné
     *
     * @Route("/recap/all/detail/{id}", name="recap_clt_detail")
     * @Method({"GET"})
     */
    public function recapDetailAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $client = $em->getRepository('OCFideliteBundle:Client')->find($id);
        $ventes = $em->getRepository('OCFideliteBundle:Vente')->findBy(array('client' => $client));

        return $this->render('OCFideliteBundle:Client:recap_client_detail.html.twig', array(
            'client' => $client,
            'ventes' => $ventes
        ));
    }
}
