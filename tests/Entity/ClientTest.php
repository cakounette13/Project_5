<?php

namespace OC\FideliteBundle\Tests\Entity;

use OC\FideliteBundle\Entity\Client;

class ClientTest extends \PHPUnit_Framework_TestCase {

    public function testSetClient() {
        $client = new Client();
        $client->setNom('DELRIEUX');
        $client->setPrenom('Carine');
        $client->setSociete('WEBB');
        $client->setCodePostal('06200');
        $client->setVille('Nice');
        $client->setPortable('0666666666');
        $client->setDateNaissance('14/02/1972');
        $client->setEmail('c@gmail.com');

        $this->assertEquals('DELRIEUX', $client->getNom());
        $this->assertEquals('Carine', $client->getPrenom());
        $this->assertEquals('WEBB', $client->getSociete());
        $this->assertEquals('06200', $client->getCodePostal());
        $this->assertEquals('Nice', $client->getVille());
        $this->assertEquals('0666666666', $client->getPortable());
        $this->assertEquals('14/02/1972', $client->getDateNaissance());
        $this->assertEquals('c@gmail.com', $client->getEmail());
    }
}
