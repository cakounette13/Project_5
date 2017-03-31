<?php

namespace OC\FideliteBundle\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class VenteControllerTest extends WebTestCase
{
    public function testPageNewVente() {

        $client = static::createClient();

        $crawler = $client->request('GET', '/vente/new');

        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /vente/new");
    }

    public function testPageAllVentes() {

        $client = static::createClient();

        $crawler = $client->request('GET', '/vente/all');

        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /vente/all");
    }

    public function testCompleteScenario()
    {
        // Création d'une nouvelle vente
        $client = static::createClient();

        $crawler = $client->request('GET', '/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /");
        $link = $crawler->selectLink('Nouvelle')->link();
        $crawler = $client->click($link);

        // Création du formulaire
        $form = $crawler->selectButton('Valider')->form(array(
            'vente[client]'  => '1',
            'vente[dateVente]' => '28/12/2017',
            'vente[montantVente]' => '126',
            'vente[pointsFideliteUtilises]' => '2'
        ));

        // Soumission du formulaire
        $client->submit($form);
    }
}
