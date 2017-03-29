<?php

namespace OC\FideliteBundle\Manager;

use Doctrine\ORM\EntityManager;
use OC\FideliteBundle\Entity\Vente;
use OC\FideliteBundle\Form\Type\VenteType;
use OC\FideliteBundle\Services\PointsFidelite;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\RequestStack;

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
     * VenteManager constructor.
     * @param EntityManager $em
     * @param PointsFidelite $pointsFidelite
     * @param FormFactory $form
     * @param RequestStack $request
     */
    public function __construct(EntityManager $em, PointsFidelite $pointsFidelite, FormFactory $form, RequestStack $request)
    {
        $this->em = $em;
        $this->pointsFidelite = $pointsFidelite;
        $this->form = $form;
        $this->request = $request;
    }

    /**
     * Insertion d'une vente
     *
     * @param Vente $vente
     *
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
     * Récupère toutes les ventes de la base de donnée
     *
     */
    public function readAll() {
        $ventes = $this->em->getRepository('OCFideliteBundle:Vente')->findAll();

        return $ventes;
    }


    /**
     * Met à jour une vente stockée en base de donnée
     *
     * @param Vente $vente
     */
    public function update(Vente $vente) {
        $request = $this->request->getCurrentRequest();

        $venteOld = $this->em->getRepository(Vente::class)->findOneBy(array('id' => $request->get('id')));
        $ancienPointsUtilises = $venteOld->getPointsFideliteUtilises();
        $editForm = $this->form->create(VenteType::class, $vente);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $pointsVente = $this->pointsFidelite->calculPointsFideliteParVente($vente);
            $ancienPointsVente = $vente->getPointFideliteVente();
            $AjustPointsClient = $pointsVente - $ancienPointsVente;
            $cumulPointsClient = $vente->getClient()->getPointsFidelite();
            $ajustPointsUtilises = $vente->getPointsFideliteUtilises() - $ancienPointsUtilises;
            $pointsClient = $cumulPointsClient + $AjustPointsClient - $ajustPointsUtilises;
            $client = $vente->getClient();
            $client->setPointsFidelite($pointsClient);
            $vente->setPointFideliteVente($pointsVente);
            $this->em->flush($vente);
            $this->em->flush($client);
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
    }
}
