<?php

namespace OC\FideliteBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Client
 *
 * @ORM\Table(name="client")
 * @ORM\Entity(repositoryClass="OC\FideliteBundle\Repository\ClientRepository")
 * @UniqueEntity("portable", message="Ce numero de portable existe déjà !")
 * @UniqueEntity("email", message="Cet email existe déjà !")
 * @UniqueEntity("societe", message="Cette société existe déjà !")
 */
class Client
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
     * @var string
     * @ORM\Column(name="denomination", type="string")
     */
    private $denomination;


    /**
     * @var string
     * @Assert\Length(min=2, minMessage="Votre nom doit comprendre au moins 2 caractères")
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     * @Assert\Length(min=2, minMessage="Votre prénom doit comprendre au moins 2 caractères")
     * @ORM\Column(name="prenom", type="string", length=255)
     */
    private $prenom;

    /**
     * @var string
     * @ORM\Column(name="societe", type="string", length=255, nullable=true, unique=true)
     */
    private $societe;

    /**
     * @var string
     * @Assert\Regex(pattern="/[0-9]{5}/", message="Votre code postal doit contenir 5 chiffres")
     * @ORM\Column(name="codePostal", type="string", length=5)
     */
    private $codePostal;

    /**
     * @var string
     * @Assert\Length(min=2, minMessage="Votre ville doit comprendre au moins 2 caractères")
     * @ORM\Column(name="ville", type="string", length=255)
     */
    private $ville;

    /**
     * @var string
     * @Assert\Regex(pattern="/[0-9]{10}/", message="Votre n° de téléphone doit contenir 10 chiffres")
     * @ORM\Column(name="portable", type="string", length=10, unique=true)
     */
    private $portable;

    /**
     * @var \DateTime()
     * @Assert\DateTime(message = "La date saisie n'est pas au bon format (ex:01/01/2050)")
     * @ORM\Column(name="dateNaissance", type="datetime")
     */
    private $dateNaissance;

    /**
     * @var string
     * @Assert\Email(message="Email invalide")
     * @ORM\Column(name="email", type="string", unique=true)
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity="OC\FideliteBundle\Entity\Vente", mappedBy="client", cascade={"persist", "remove", "merge"})
     */
    private $ventes;

    /**
     * @var int
     * @ORM\Column(name="nbrVentes", type="integer", nullable=true)
     */
    private $nbrVentes = 0;


    /**
     * @var float
     * @ORM\Column(name="pointsFidelite", type="float", scale=2, nullable=true)
     */
    private $pointsFidelite = 0;


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
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Client
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     *
     * @return Client
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set codePostal
     *
     * @param string $codePostal
     *
     * @return Client
     */
    public function setCodePostal($codePostal)
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    /**
     * Get codePostal
     *
     * @return string
     */
    public function getCodePostal()
    {
        return $this->codePostal;
    }

    /**
     * Set ville
     *
     * @param string $ville
     *
     * @return Client
     */
    public function setVille($ville)
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get ville
     *
     * @return string
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * Set portable
     *
     * @param string $portable
     *
     * @return Client
     */
    public function setPortable($portable)
    {
        $this->portable = $portable;

        return $this;
    }

    /**
     * Get portable
     *
     * @return string
     */
    public function getPortable()
    {
        return $this->portable;
    }

    /**
     * Set dateNaissance
     *
     * @param \DateTime $dateNaissance
     *
     * @return Client
     */
    public function setDateNaissance($dateNaissance)
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    /**
     * Get dateNaissance
     *
     * @return \DateTime
     */
    public function getDateNaissance()
    {
        return $this->dateNaissance;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Client
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set societe
     *
     * @param string $societe
     *
     * @return Client
     */
    public function setSociete($societe)
    {
        $this->societe = $societe;

        return $this;
    }

    /**
     * Get societe
     *
     * @return string
     */
    public function getSociete()
    {
        return $this->societe;
    }

    /**
     * Set denomination
     *
     * @param string $denomination
     *
     * @return Client
     */
    public function setDenomination($denomination)
    {
        $this->denomination = $denomination;

        return $this;
    }

    /**
     * Get denomination
     *
     * @return string
     */
    public function getDenomination()
    {
        return $this->denomination;
    }

    /**
     * @return ArrayCollection
     */
    public function getVentes()
    {
        return $this->ventes;
    }

    public function __construct()
    {
        $this->ventes = new ArrayCollection();
    }

    /**
     * @param Vente $vente
     */
    public function addVente(Vente $vente)
    {
        $this->ventes[]= $vente;
        $vente->setClient($this);
    }

    /**
     * @param Vente $vente
     */
    public function removeVente(Vente $vente) {
        $this->ventes->removeElement($vente);
    }

    public function getNbrVentes() {
        $this->nbrVentes;
    }

    public function setNbrVentes($nbrVentes) {
        $this->nbrVentes = $nbrVentes;

    }

    public function ajouteNbrVentes() {
        $this->nbrVentes++;
    }

    public function deduitNbrVentes() {
        $this->nbrVentes--;
    }
    /**
     * @return float
     */
    public function getPointsFidelite()
    {
        return $this->pointsFidelite;
    }

    /**
     * @param float $pointsFidelite
     */
    public function setPointsFidelite($pointsFidelite)
    {
        $this->pointsFidelite = $pointsFidelite;
    }

}
