<?php

namespace OC\FideliteBundle\Manager;

use Doctrine\ORM\EntityManager;
use OC\FideliteBundle\Entity\Vente;
use Symfony\Component\DependencyInjection\ContainerInterface;

class VenteManager
{
    protected $container;

    protected $em;

    /**
     * Initialisation de la connexion à la base de donnée
     * @param EntityManager $em
     * @param ContainerInterface $container
     */
    public function __construct(EntityManager $em, ContainerInterface $container)
    {
        $this->em = $em;
        $this->container = $container;
    }

    /**
     * Insertion d'une vente
     *
     * @param Vente $vente
     *
     */
    public function addVente(Vente $vente) {
//        $em = $this->em->getDoctrine()->getManager();
        $this->em->persist($vente);
        $points = $this->container->get('oc_fidelite.points_fidelite')->calculPointsFidelite($vente);
        $vente->setPointFidelite($points);
        $this->em->flush($vente);
        return $vente;
    }

    /**
     * Récupère une vente à partir de son identifiant
     *
     * @param $id
     */
    public function read($id) {
        return $this->em->getRepository()->findOneBy(array('id' => $id));
    }

    /**
     * Récupère toutes les ventes de la base de donnée
     *
     * @param $id
     */
    public function readAll() {

    }


    /**
     * Met à jour une vente stockée en base de donnée
     *
     * @param Vente $vente
     */
    public function update(Vente $vente) {

    }

    /**
    * Supprime une vente stockée en base de donnée
    *
    * @param Vente $vente
    */
    public function delete(Vente $vente) {

    }
}