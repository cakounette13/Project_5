<?php

namespace OC\FideliteBundle\Tests\Entity;

use OC\FideliteBundle\Entity\Client;

class ClientTest extends \PHPUnit_Framework_TestCase {

    public function testNewClient() {
        $client = new Client();

        $this->assertEquals(null, $client->getNom());
        $this->assertEquals(null, $client->getPrenom());
        $this->assertEquals(null, $client->getSociete());
        $this->assertEquals(null, $client->getCodePostal());
        $this->assertEquals(null, $client->getVille());
        $this->assertEquals(null, $client->getPortable());
        $this->assertEquals(null, $client->getDateNaissance());
        $this->assertEquals(null, $client->getEmail());
    }

    public function testSetClient() {
        $client = new Client();
        $nom = $client->setNom('DELRIEUX');
        $prenom = $client->setPrenom('Carine');
        $societe = $client->setSociete('WEBB');
        $codePostal = $client->setCodePostal('06200');
        $ville = $client->setVille('Nice');
        $portable = $client->setPortable('0666666666');
        $dateNaissance = $client->setDateNaissance('14/02/1972');
        $email = $client->setEmail('c@gmail.com');

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
