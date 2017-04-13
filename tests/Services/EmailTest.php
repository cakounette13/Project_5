<?php
namespace Tests\Services;

use Doctrine\ORM\EntityManager;
use OC\FideliteBundle\Entity\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EmailTest extends WebTestCase
{
    // test ne pouvant être fait qu'une fois
    /**
     * @var EntityManager
     */
    private $em;

    public function setUp()
    {
        self::bootKernel();

        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $client = new Client();
        $client->setDenomination('Madame');
        $client->setNom('DELRIEUX');
        $client->setPrenom('Carine');
        $client->setSociete('WEB');
        $client->setCodePostal('06200');
        $client->setVille('Nice');
        $client->setPortable('0666666664');
        $client->setDateNaissance(\DateTime::createFromFormat('d/m/Y','13/04/2017')); // Mettre la date du jour du test
        $client->setEmail('carine@gmail.com');
        $client->setMailEnvoyeLe(\DateTime::createFromFormat('d/m/Y','30/03/2017'));
        $this->em->persist($client);
        $this->em->flush($client);
    }

    public function testEnvoiMailI()
    {
        $requete = static::createClient();

        $requete->enableProfiler();

       $crawler = $requete->request('GET', '/');
        $mailCollector = $requete->getProfile()->getCollector('swiftmailer');

        // Check that an email was sent
        $this->assertEquals(1, $mailCollector->getMessageCount());

        $collectedMessages = $mailCollector->getMessages();
        $message = $collectedMessages[0];

        // Asserting email data
        $this->assertInstanceOf('Swift_Message', $message);
        $this->assertEquals('CAVE ... - Cadeau d\'anniversaire', $message->getSubject());
        $this->assertEquals('carinedelrieux@gmail.fr', key($message->getFrom()));
        $this->assertEquals('carine@gmail.com', key($message->getTo()));
    }
}
