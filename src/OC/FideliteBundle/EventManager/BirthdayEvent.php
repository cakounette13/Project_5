<?php

namespace OC\FideliteBundle\EventManager;

use OC\FideliteBundle\Entity\Client;
use Symfony\Component\EventDispatcher\Event;

class BirthdayEvent extends Event
{
    const NAME = 'birthday_event';

    /**
     * @var Client
     */
    public $client;

    /**
     * BirthdayEvent constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }
}
