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
 * @Route("/")
 */
class ClientController extends Controller
{
    /**
     * @Route("/", name="accueil")
     *
     */
    public function indexAction()
    {
        return $this->render('base.html.twig');
    }

    /**
     * Lists all client entities.
     *
     * @Route("/all", name="all_clt")
     * @Method("GET")
     */
    public function allAction()
    {
        $em = $this->getDoctrine()->getManager();

        $client = $em->getRepository('OCFideliteBundle:Client')->findAll();
        $clients = $em->getRepository('OCFideliteBundle:Client')->getAllClientsParOrdre($client);

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
    public function newAction(Request $request)
    {
        $client = new Client();
        $form = $this->createForm('OC\FideliteBundle\Form\Type\ClientType', $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($client);
            $em->flush($client);

            $this->get('ras_flash_alert.alert_reporter')->addSuccess("Nouveau Client créé !");

            return $this->redirectToRoute('accueil', array(
                'id' => $client->getId()));
        }

        return $this->render('OCFideliteBundle:Client:new_clt.html.twig', array(
            'client' => $client,
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
        $deleteForm = $this->createDeleteForm($client);

        return $this->render('OCFideliteBundle:Client:show_clt.html.twig', array(
            'client' => $client,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing client entity.
     *
     * @Route("/{id}/edit", name="edit_clt")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Client $client)
    {
        $deleteForm = $this->createDeleteForm($client);
        $editForm = $this->createForm('OC\FideliteBundle\Form\Type\ClientType', $client);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->get('ras_flash_alert.alert_reporter')->addSuccess("Fiche Client modifiée !");
            return $this->redirectToRoute('all_clt', array('id' => $client->getId()));
        }

        return $this->render('OCFideliteBundle:Client:edit_clt.html.twig', array(
            'client' => $client,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a client entity.
     *
     * @Route("/{id}", name="delete_clt")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Client $client)
    {
        $form = $this->createDeleteForm($client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($client);
            $em->flush($client);
        }

        $this->get('ras_flash_alert.alert_reporter')->addError("Fiche Client supprimée !");

        return $this->redirectToRoute('all_clt');
    }

    /**
     * Creates a form to delete a client entity.
     *
     * @param Client $client The client entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Client $client)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('delete_clt', array('id' => $client->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
