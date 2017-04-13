<?php

namespace OC\FideliteBundle\Tests\Entity;

use OC\FideliteBundle\Entity\Client;
use OC\FideliteBundle\Entity\Vente;

class VenteTest extends \PHPUnit_Framework_TestCase
{
    public function testSetVente()
    {
        $vente = new Vente();
        $client = new Client();
        $nom = $client->setNom('carine');
        $vente->setClient($nom);
        $vente->setDateVente('31/05/2018');
        $vente->setMontantVente('420.15');
        $vente->setPointsFideliteUtilises('17.20');

        $this->assertEquals($client, $vente->getClient());
        $this->assertEquals('31/05/2018', $vente->getDateVente());
        $this->assertEquals('420.15', $vente->getMontantVente());
        $this->assertEquals('17.20', $vente->getPointsFideliteUtilises());
    }

}
