<?php

namespace OC\FideliteBundle\EventManager;

use Symfony\Component\EventDispatcher\Event;

class BirthdayEvent extends Event
{
    /**
     * @var \DateTime
     */
    protected $dateNaissance;

    /**
     * @return \DateTime
     */
    public function getDateNaissance()
    {
        return $this->dateNaissance;
    }

    /**
     * @param \DateTime $dateNaissance
     */
    public function setDateNaissance($dateNaissance)
    {
        $this->dateNaissance = $dateNaissance;
    }
}