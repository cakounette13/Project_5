<?php

namespace OC\FideliteBundle\Manager;

use Doctrine\ORM\EntityManager;
use OC\FideliteBundle\Entity\Vente;
use OC\FideliteBundle\Form\Type\VenteType;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\RequestStack;

class VenteManager
{
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
     * VenteManager constructor.
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
            $pointsVente = $this->container->get('oc_fidelite.points_fidelite')->calculPointsFideliteParVente($vente);
            $points = $this->container->get('oc_fidelite.points_fidelite')->calculCumulPointsFidelite($vente);
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

        $editForm = $this->form->create(VenteType::class, $vente);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $pointsVente = $this->container->get('oc_fidelite.points_fidelite')->calculPointsFideliteParVente($vente);
            $ancienPointsVente = $vente->getPointFideliteVente();
            $AjustPointsClient = $pointsVente - $ancienPointsVente;
            $cumulPointsClient = $vente->getClient()->getPointsFidelite();
            $pointsClient = $cumulPointsClient + $AjustPointsClient;
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
