<?php

namespace OC\FideliteBundle\Manager;

use Doctrine\ORM\EntityManager;
use OC\FideliteBundle\Entity\Vente;
use OC\FideliteBundle\Form\Type\VenteType;
use OC\FideliteBundle\Services\PointsFidelite;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;

class VenteManager
{
    /**
     * @var PointsFidelite
     */
    private $pointsFidelite;

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
     * VenteManager constructor.
     *
     * @param EntityManager $em
     * @param PointsFidelite $pointsFidelite
     * @param FormFactory $form
     * @param RequestStack $request
     */
    public function __construct(EntityManager $em, PointsFidelite $pointsFidelite, FormFactory $form, RequestStack $request, Session $session)
    {
        $this->em = $em;
        $this->pointsFidelite = $pointsFidelite;
        $this->form = $form;
        $this->request = $request;
        $this->session =$session;
    }

    /**
     * Insertion d'une vente
     *
     * @param Vente $vente
     */
    public function add() {
        $request = $this->request->getCurrentRequest();

        $vente = new Vente();
        $form = $this->form->create(VenteType::class, $vente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($vente);
            $pointsVente = $this->pointsFidelite->calculPointsFideliteParVente($vente);
            $points = $this->pointsFidelite->calculCumulPointsFidelite($vente);
            $client = $vente->getClient()->setPointsFidelite($points);
            $client = $vente->getClient()->ajouteNbrVentes();
            $vente = $vente->setPointFideliteVente($pointsVente);
            $this->em->flush($vente);
            $this->em->flush($client);

            $this->session->getFlashBag()->add('success', "Vente enregistrée !");
        }
        return $form;
    }

    /**
     * Récupère une vente à partir de son identifiant
     *
     * @param $id
     */
    public function read($id) {
        return $this->em->getRepository('OCFideliteBundle:Vente')->findOneBy(array('id' => $id));
    }

    /**
     * Récupère toutes les ventes de la BDD
     *
     * @return array|Vente[]
     */
    public function readAll() {
        $ventes = $this->em->getRepository('OCFideliteBundle:Vente')->findAll();

        return $ventes;
    }

    /**
     * Modifie une vente stockée en base de donnée
     *
     * @param Vente $vente
     */
    public function update(Vente $vente) {
        $request = $this->request->getCurrentRequest();

        $venteOld = $this->em->getRepository(Vente::class)->findOneBy(array('id' => $request->get('id')));
        $ancienPointsUtilises = $venteOld->getPointsFideliteUtilises();
        $ancienClient = $venteOld->getCLient();
        $editForm = $this->form->create(VenteType::class, $vente);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $newClient = $vente->getClient();
            // Si modification du client
            if ($ancienClient != $newClient ) {
                // On retire à l'ancien client la vente
                $nbrVentes = $ancienClient->getNbrVentes()-1;
                $ancienPointsVente = $vente->getPointFideliteVente();
                $cumulPointsAncienClient = $ancienClient->getPointsFidelite() - $ancienPointsVente + $ancienPointsUtilises;
                $ancienClient->setNbrVentes($nbrVentes);
                $ancienClient->setPointsFidelite($cumulPointsAncienClient);
                $newClient->addVente($vente);
                $newClient->ajouteNbrVentes();
                $pointsVente = $this->pointsFidelite->calculPointsFideliteParVente($vente);
                $oldPointsNewClient = $vente->getClient()->getPointsFidelite();
                $pointsUtilises = $vente->getPointsFideliteUtilises();
                $ajustPointsNewClient = $pointsVente + $oldPointsNewClient - $pointsUtilises;
                $newClient->setPointsFidelite($ajustPointsNewClient);
                $vente->setPointFideliteVente($pointsVente);
                $vente->setPointsFideliteUtilises($pointsUtilises);

                $this->em->flush($ancienClient);
                $this->em->flush($newClient);
                $this->em->flush($vente);

                $this->session->getFlashBag()->add('success', "Vente modifiée !");
            } else {
                // Ajuste points fidélités si montant de la vente modifié
                $pointsVente = $this->pointsFidelite->calculPointsFideliteParVente($vente);
                $ancienPointsVente = $vente->getPointFideliteVente();
                // points sur la vente
                $ajustPointsClient = $pointsVente - $ancienPointsVente;
                // Cumul des points du client
                $cumulPointsClient = $vente->getClient()->getPointsFidelite();
                // Ajuste points utilisés modifiés
                $ajustPointsUtilises = $vente->getPointsFideliteUtilises() - $ancienPointsUtilises;
                $pointsClient = $cumulPointsClient + $ajustPointsClient - $ajustPointsUtilises;
                $client = $vente->getClient();
                $client->setPointsFidelite($pointsClient);
                $vente->setPointFideliteVente($pointsVente);

                $this->em->flush($vente);
                $this->em->flush($client);

                $this->session->getFlashBag()->add('success', "Vente modifiée !");
            }
        }
        return $editForm;
    }

    /**
    * Supprime une vente stockée en base de donnée
    *
    * @param Vente $vente
    */
    public function delete(Vente $vente) {
        $client = $vente->getClient();

        $nbrVentes = $client->getNbrVentes();
        $nbrVentes = $nbrVentes - 1;
        $client->setNbrVentes($nbrVentes);

        $pointsClient = $client->getPointsFidelite();
        $pointsVente = $vente->getPointFideliteVente();
        $pointsUtilises = $vente->getPointsFideliteUtilises();
        $points = $pointsClient - $pointsVente + $pointsUtilises;
        $client->setPointsFidelite($points);

        $this->em->remove($vente);
        $this->em->flush($vente);
        $this->em->flush($client);

        $this->session->getFlashBag()->add('danger', "Vente supprimée !");
    }
}
