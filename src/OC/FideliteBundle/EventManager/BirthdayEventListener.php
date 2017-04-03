<?php

namespace OC\FideliteBundle\EventManager;


use OC\FideliteBundle\Entity\Client;
use OC\FideliteBundle\Services\Email;

class BirthdayEventListener
{
    /**
     * @var Email
     */
    private $email;

    public function __construct(Email $email)
    {
        $this->email = $email;
    }

    public function activeEvent(BirthdayEvent $event, Client $client) {

        $dateNaissance = $event->getDateNaissance();
        $dateJour = new \DateTime();

        if ($dateNaissance == $dateJour) {
            $this->email->envoiMail($client);
        }
    }

}