<?php

namespace Tests\Services;

use OC\FideliteBundle\Repository\ClientRepository;
use OC\FideliteBundle\Repository\VenteRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PointsFideliteTest extends WebTestCase
{
    /**
     * @var ClientRepository
     *
     */
    private $clientRepository;

    /**
     * @var VenteRepository
     */
    private $venteRepository;

    public function setUp() {
        $kernel = static::createKernel();
        $kernel->boot();
        $this->clientRepository = $kernel->getContainer()
            ->get('doctrine.orm.entity_manager')
            ->getRepository('OCFideliteBundle:Client');
        $this->venteRepository = $kernel->getContainer()
            ->get('doctrine.orm.entity_manager')
            ->getRepository('OCFideliteBundle:Vente');
    }

    public function testCalculPointsFideliteParVente() {

        $request = static::createClient();
        $clients = $this->clientRepository->findAll();
        $client = $clients['0'];
        $vente = $this->venteRepository->find($client);
        $request->getContainer()->get('oc_fidelite.points_fidelite')->calculPointsFideliteParVente($vente);


        $this->assertEquals('5', $vente->getPointsFideliteUtilises());
        $this->assertEquals('6', $vente->getPointFideliteVente());

    }

    public function testCalculCumulPointsFidelite() {

        $request = static::createClient();
        $clients = $this->clientRepository->findAll();
        $client = $clients['0'];
        $vente = $this->venteRepository->find($client);
        $request->getContainer()->get('oc_fidelite.points_fidelite')->calculCumulPointsFidelite($vente);

        $this->assertEquals('22.5', $vente->getClient()->getPointsFidelite());
    }

}