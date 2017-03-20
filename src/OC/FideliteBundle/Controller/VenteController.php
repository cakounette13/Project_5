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
        $em = $this->getDoctrine()->getManager();

        $ventes = $em->getRepository('OCFideliteBundle:Vente')->findAll();

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
    public function newAction(Request $request)
    {
        $vente = new Vente();
        $form = $this->createForm('OC\FideliteBundle\Form\Type\VenteType', $vente);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($vente);
            $points = $this->get('oc_fidelite.points_fidelite')->calculPointsFidelite($vente);
            $vente->setPointFidelite($points);
            $em->flush($vente);

            return $this->redirectToRoute('show_vte', array('id' => $vente->getId()));
        }

        return $this->render('OCFideliteBundle:Vente:new_vte.html.twig', array(
            'vente' => $vente,
            'form' => $form->createView(),
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
        $deleteForm = $this->createDeleteForm($vente);

        return $this->render('OCFideliteBundle:Vente:show_vte.html.twig', array(
            'vente' => $vente,
            'delete_form' => $deleteForm->createView(),
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
        $deleteForm = $this->createDeleteForm($vente);
        $editForm = $this->createForm('OC\FideliteBundle\Form\Type\VenteType', $vente);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('edit_vte', array('id' => $vente->getId()));
        }

        return $this->render('OCFideliteBundle:Vente:edit_vte.html.twig', array(
            'vente' => $vente,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a vente entity.
     *
     * @Route("/{id}", name="delete_vte")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Vente $vente)
    {
        $form = $this->createDeleteForm($vente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($vente);
            $em->flush($vente);
        }

        return $this->redirectToRoute('accueil');
    }

    /**
     * Creates a form to delete a vente entity.
     *
     * @param Vente $vente The vente entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Vente $vente)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('delete_vte', array('id' => $vente->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
