<?php

namespace OC\FideliteBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use OC\FideliteBundle\Entity\Client;
use OC\FideliteBundle\Entity\Vente;

class LoadFixtures implements FixtureInterface
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
        $client->setNbrVentes('4');
        $client->setPointsFidelite('22.5');
        $client->setMailEnvoyeLe(\DateTime::createFromFormat('d/m/Y','14/02/2017'));

        $vente1 = new Vente();
        $vente1->setClient($client);
        $vente1->setDateVente(\DateTime::createFromFormat('d/m/Y','01/02/2017'));
        $vente1->setMontantVente('100');
        $vente1->setPointFideliteVente('6');
        $vente1->setPointsFideliteUtilises('5');

        $vente2 = new Vente();
        $vente2->setClient($client);
        $vente2->setDateVente(\DateTime::createFromFormat('d/m/Y','17/03/2017'));
        $vente2->setMontantVente('100');
        $vente2->setPointFideliteVente('6');
        $vente2->setPointsFideliteUtilises('1');

        $vente3 = new Vente();
        $vente3->setClient($client);
        $vente3->setDateVente(\DateTime::createFromFormat('d/m/Y','19/03/2017'));
        $vente3->setMontantVente('150');
        $vente3->setPointFideliteVente('9');
        $vente3->setPointsFideliteUtilises('0');

        $vente4 = new Vente();
        $vente4->setClient($client);
        $vente4->setDateVente(\DateTime::createFromFormat('d/m/Y','30/03/2017'));
        $vente4->setMontantVente('125');
        $vente4->setPointFideliteVente('7.5');
        $vente4->setPointsFideliteUtilises('0');

        $client2 = new Client();
        $client2->setDenomination('Monsieur');
        $client2->setNom('DELRIEUX');
        $client2->setPrenom('Didier');
        $client2->setDateNaissance(\DateTime::createFromFormat('d/m/Y','06/05/1971'));
        $client2->setCodePostal('13013');
        $client2->setVille('Marseille');
        $client2->setPortable('0663704309');
        $client2->setEmail('didierdelrieux@gmail.com');
        $client2->setMailEnvoyeLe(\DateTime::createFromFormat('d/m/Y','14/02/2017'));

        $client3 = new Client();
        $client3->setDenomination('Société');
        $client3->setNom('DELRIEUX');
        $client3->setPrenom('Didier');
        $client3->setSociete('AG3D');
        $client3->setDateNaissance(\DateTime::createFromFormat('d/m/Y','06/05/1971'));
        $client3->setCodePostal('13013');
        $client3->setVille('Marseille');
        $client3->setPortable('0663704307');
        $client3->setEmail('didierdelrieux@free.fr');
        $client3->setNbrVentes('1');
        $client3->setPointsFidelite('15.69');
        $client3->setMailEnvoyeLe(\DateTime::createFromFormat('d/m/Y','14/02/2017'));

        $vente5 = new Vente();
        $vente5->setClient($client3);
        $vente5->setDateVente(\DateTime::createFromFormat('d/m/Y','19/03/2017'));
        $vente5->setMontantVente('261.54');
        $vente5->setPointFideliteVente('15.69');
        $vente5->setPointsFideliteUtilises('0');

        $manager->persist($client);
        $manager->persist($client2);
        $manager->persist($client3);
        $manager->persist($vente1);
        $manager->persist($vente2);
        $manager->persist($vente3);
        $manager->persist($vente4);
        $manager->persist($vente5);

        $manager->flush();
    }
}
