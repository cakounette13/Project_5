<?php
namespace Tests\Services;

use OC\FideliteBundle\Entity\Client;
use OC\FideliteBundle\Services\Email;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EmailTest extends WebTestCase
{
    /**
     * @var Email
     */
    private $email;

    /**
     * @var ClientRepository
     *
     */
    private $clientRepository;

    public function setUp()
    {
        $kernel = static::createKernel();
        $kernel->boot();
        $this->clientRepository = $kernel->getContainer()
            ->get('doctrine.orm.entity_manager')
            ->getRepository('OCFideliteBundle:Client');
//        parent::setUp(); //
//        $client = new Client();
//        $client->setNom('DELRIEUX');
//        $client->setPrenom('Carine');
//        $client->setSociete('WEBB');
//        $client->setCodePostal('06200');
//        $client->setVille('Nice');
//        $client->setPortable('0666666666');
//        $client->setDateNaissance('NOW');
//        $client->setEmail('c@gmail.com');
//        $client->setMailEnvoyeLe('11/04/2017');
    }

    public function testEnvoiMailI()
    {
        $requete = static::createClient();

        $clients = $this->clientRepository->findAll();
        $client = $clients['0'];
        $requete->getContainer()->get('oc_fidelite.email')->envoiMail($client);

//        $requete->enableProfiler();
//
//        $crawler = $requete->request('GET', '/');
        $mailCollector = $requete->getProfile()->getCollector('swiftmailer');

        // Check that an email was sent
        $this->assertEquals(1, $this->getMessageCount());

        $collectedMessages = $mailCollector->getMessages();
        $message = $collectedMessages[0];

        // Asserting email data
        $this->assertInstanceOf('Swift_Message', $message);
        $this->assertEquals('CAVE ... - Cadeau d\'anniversaire', $message->getSubject());
        $this->assertEquals('carinedelrieux@gmail.fr', key($message->getFrom()));
        $this->assertEquals('carinedelrieux@gmail.com', key($message->getTo()));
    }
}
