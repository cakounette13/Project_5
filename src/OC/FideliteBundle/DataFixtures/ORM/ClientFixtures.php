<?php

namespace OC\FideliteBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use OC\FideliteBundle\Entity\Client;


class ClientFixtures implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $client = new Client();
        $client->setDenomination('Madame');
        $client->setNom('DELRIEUX');
        $client->setPrenom('Carine');
        $client->setDateNaissance(\DateTime::createFromFormat('d/m/Y','14/02/1972'));
        $client->setCodePostal('13013');
        $client->setVille('Marseille');
        $client->setPortable('0662401355');
        $client->setEmail('carinedelrieux@gmail.com');

        $client2 = new Client();
        $client2->setDenomination('Monsieur');
        $client2->setNom('DELRIEUX');
        $client2->setPrenom('Didier');
        $client2->setDateNaissance(\DateTime::createFromFormat('d/m/Y','06/05/1971'));
        $client2->setCodePostal('13013');
        $client2->setVille('Marseille');
        $client2->setPortable('0663704309');
        $client2->setEmail('didierdelrieux@gmail.com');

        $client3 = new Client();
        $client3->setDenomination('Société');
        $client3->setNom('DELRIEUX');
        $client3->setPrenom('Didier');
        $client3->setSociete('AG3D');
        $client3->setDateNaissance(\DateTime::createFromFormat('d/m/Y','06/05/1971'));
        $client3->setCodePostal('13013');
        $client3->setVille('Marseille');
        $client3->setPortable('0663704309');
        $client3->setEmail('didierdelrieux@gmail.com');

        $manager->persist($client);
        $manager->persist($client2);
        $manager->persist($client3);
        $manager->flush();
    }
}
