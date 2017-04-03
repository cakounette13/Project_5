<?php
namespace OC\FideliteBundle\Anniversaire;

use OC\FideliteBundle\Entity\Client;
use OC\FideliteBundle\Services\Email;
use Symfony\Component\HttpKernel\Event\PostResponseEvent;

class AnnivClientListener  {

    /**
     * @var Email
     */
    private $email;

    /**
     * @var \DateTime
     */
    private $dateJour;

    public function __construct(Email $email,\DateTime $dateJour)
    {
        $this->email = $email;
        $this->dateJour = $dateJour;
    }


    public function activeEvent(PostResponseEvent $event, Client $client) {
        // On teste si la requête est bien la requête principale (et non une sous-requête)
        if (!$event->isMasterRequest()) {
            return;
        }

        $dateNaissance = $client->getDateNaissance();

        if ($dateNaissance == $this->dateJour) {
            $this->email->envoiMail($client);
        }
   }
}
