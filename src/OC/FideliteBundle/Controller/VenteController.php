<?php

namespace OC\FideliteBundle\Controller;

use OC\FideliteBundle\Entity\Vente;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Vente controller.
 *
 * @Route("vente")
 */
class VenteController extends Controller
{
    /**
     * Lists all vente entities.
     *
     * @Route("/all", name="all_vte")
     * @Method("GET")
     */
    public function allAction()
    {
        $ventes = $this->get('oc_fidelite.vente_manager')->readAll();

        return $this->render('OCFideliteBundle:Vente:all_vte.html.twig', array(
            'ventes' => $ventes,
        ));
    }

    /**
     * Creates a new vente entity.
     *
     * @Route("/new", name="new_vte")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request) {

        $form = $this->get('oc_fidelite.vente_manager')->add();

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('accueil');
        }

        return $this->render('OCFideliteBundle:Vente:new_vte.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * Finds and displays a vente entity.
     *
     * @Route("/{id}", name="show_vte")
     * @Method("GET")
     */
    public function showAction(Vente $vente)
    {
        $vente = $this->get('oc_fidelite.vente_manager')->read($vente);

        return $this->render('OCFideliteBundle:Vente:show_vte.html.twig', array(
            'vente' => $vente,
        ));
    }

    /**
     * Displays a form to edit an existing vente entity.
     *
     * @Route("/{id}/edit", name="edit_vte")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Vente $vente)
    {
        $editForm = $this->get('oc_fidelite.vente_manager')->update($vente);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            return $this->redirectToRoute('all_vte', array('id' => $vente->getId()));
        }

        return $this->render('OCFideliteBundle:Vente:edit_vte.html.twig', array(
            'vente' => $vente,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a vente entity.
     *
     * @Route("/suppr/{id}", name="delete_vte")
     */
    public function deleteAction(Request $request, Vente $vente)
    {
        $this->get('oc_fidelite.vente_manager')->delete($vente);

        return $this->redirectToRoute('accueil');
    }
}
