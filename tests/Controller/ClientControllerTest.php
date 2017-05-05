<?php

namespace OC\FideliteBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ClientControllerTest extends WebTestCase
{
    public function testPageNewClient() {

        $client = static::createClient();

        $crawler = $client->request('GET', '/client/new');

        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /client/new");
    }

    public function testPageAllClients() {

        $client = static::createClient();

        $crawler = $client->request('GET', '/client/all');

        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /client/all");
    }

    public function testCreationClient()
    {
        $client = static::createClient();

        // Connexion à la base de données avec ses identifiants
        $crawler = $client->request('GET', '/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /");
        $link = $crawler->filter('a:contains("Connexion")')->link();
        $crawler = $client->click($link);

        $form = $crawler->selectButton('Connexion')->form(array(
            'login_form[_username]'=> 'carine',
            'login_form[_password]' => 'qwerty'
        ));
        $client->submit($form);
        $crawler = $client->request('POST', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /");
        $link = $crawler->filter('a:contains("Nouveau client")')->link();
        $crawler = $client->click($link);
        // Créer un nouveau client
        // Creation du formulaire
        $form = $crawler->selectButton('Valider')->form(array(
            'client[denomination]'=> 'Madame',
            'client[nom]' => 'DURAND',
            'client[prenom]' => 'Bernard.',
            'client[societe]' => 'Bourvence',
            'client[codePostal]' => '06200',
            'client[ville]' => 'Plan de Cuques',
            'client[portable]' => '0699887766',
            'client[dateNaissance]' => '14/02/1972',
            'client[email]' => 'c@gmail.com',
        ));

        // Soumission du formulaire
        $client->submit($form);
    }

    public function testVueDeTousLesClients() {
        $client = static::createClient();

        // Connexion à la base de données avec ses identifiants
        $crawler = $client->request('GET', '/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /");
        $link = $crawler->filter('a:contains("Connexion")')->link();
        $crawler = $client->click($link);

        $form = $crawler->selectButton('Connexion')->form(array(
            'login_form[_username]'=> 'carine',
            'login_form[_password]' => 'qwerty'
        ));
        $client->submit($form);
        $crawler = $client->request('POST', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /");
        $link = $crawler->selectLink('Gestion des clients')->link();
        $crawler = $client->click($link);

        // Verification de la nouvelle url
        $this->assertEquals('/client/all', '/client/all' );
    }
}
