<?php

namespace OC\FideliteBundle\EventManager;

use OC\FideliteBundle\Services\Email;

class BirthdayEventListener
{
    /**
     * @var Email
     */
    private $email;

    /**
     * BirthdayEventListener constructor.
     * @param Email $email
     */
    public function __construct(Email $email)
    {
        $this->email = $email;
    }

    public function activeEvent(BirthdayEvent $event)
    {
        if ($event->isPropagationStopped()) {
            return;
        } else {
            $dateNaissance = $event->getClient()->getDateNaissance();
            $dateNaissance = $dateNaissance->format('d m');
            $dateJour = date('d m');

            // Vérification si date d'anniversaire client égale date du jour
            if ($dateNaissance == $dateJour) {
                $this->email->envoiMail($event->getClient());
            }
        }
    }
}
