<?php

namespace OC\FideliteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Vente
 *
 * @ORM\Table(name="vente")
 * @ORM\Entity(repositoryClass="OC\FideliteBundle\Repository\VenteRepository")
 */
class Vente
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateVente", type="datetime")
     */
    private $dateVente;

    /**
     * @var float
     *
     * @ORM\Column(name="montantVente", type="float", scale=2)
     */
    private $montantVente;


    /**
     * @ORM\ManyToOne(targetEntity="OC\FideliteBundle\Entity\Client", inversedBy="ventes", cascade={"persist", "merge"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $client;

    /**
     * @var float
     *
     * @ORM\Column(name="pointFideliteVente", type="float", scale=2)
     */
    private $pointFideliteVente;

    /**
     * @var float
     * @ORM\Column(name="pointsFideliteUtilises", type="float", scale=2, nullable=true)
     */

    private $pointsFideliteUtilises = 0;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set dateVente
     *
     * @param \DateTime $dateVente
     *
     * @return Vente
     */
    public function setDateVente($dateVente)
    {
        $this->dateVente = $dateVente;

        return $this;
    }

    /**
     * Get dateVente
     *
     * @return \DateTime
     */
    public function getDateVente()
    {
        return $this->dateVente;
    }

    /**
     * Set montantVente
     *
     * @param float $montantVente
     *
     * @return Vente
     */
    public function setMontantVente($montantVente)
    {
        $this->montantVente = $montantVente;

        return $this;
    }

    /**
     * Get montantVente
     *
     * @return float
     */
    public function getMontantVente()
    {
        return $this->montantVente;
    }


    /**
     * Set client
     *
     * @param \OC\FideliteBundle\Entity\Client $client
     *
     * @return Vente
     */
    public function setClient(Client $client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return \OC\FideliteBundle\Entity\Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @return float
     */
    public function getPointFideliteVente()
    {
        return $this->pointFideliteVente;
    }

    /**
     * @param float $pointFidelite
     */
    public function setPointFideliteVente($pointFideliteVente)
    {
        $this->pointFideliteVente = $pointFideliteVente;
    }

    /**
     * @return float
     */
    public function getPointsFideliteUtilises()
    {
        return $this->pointsFideliteUtilises;
    }

    /**
     * @param float $pointsFideliteUtilises
     */
    public function setPointsFideliteUtilises($pointsFideliteUtilises)
    {
        $this->pointsFideliteUtilises = $pointsFideliteUtilises;
    }

}
