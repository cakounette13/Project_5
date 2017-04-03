<?php

namespace Tests\Services;

use OC\FideliteBundle\Entity\Client;
use OC\FideliteBundle\Entity\Vente;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PointsFideliteTest extends WebTestCase
{
    protected $client;
    protected $vente;

    public function setUp() {
        $this->vente = new Vente();
        $this->vente->setMontantVente('100');
        $this->vente->setPointsFideliteUtilises('2');

        $client = new Client();
        $client->setId('1');
        $client->setPointsFidelite(10);
        $client->addVente($this->vente);
        $ventes = array();
        $ventes[0] = $this->vente;

        var_dump($this->vente);
        die();
    }

    public function testCalculPointsFideliteParVente() {

        $client = static::createClient();
        $container = $client->getContainer();
        $container->get('oc_fidelite.points_fidelite')->calculPointsFideliteParVente($this->vente);

        $this->assertEquals('6', $this->vente->getPointFideliteVente());
        $this->assertEquals('2', $this->vente->getPointsFideliteUtilises());
    }

    public function testCalculCumulPointsFidelite() {

        $client = static::createClient();
        $container = $client->getContainer();
        $container->get('oc_fidelite.points_fidelite')->calculCumulPointsFidelite($this->vente);

        $this->assertEquals('14', $this->client->getPointsFidelite());
    }

}