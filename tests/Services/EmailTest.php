<?php
namespace Tests\Services;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EmailTest extends WebTestCase
{
    public function testEnvoiMailI()
    {
        $client = static::createClient();

        // Enable the profiler for the next request (it does nothing if the profiler is not available)
        $client->enableProfiler();

        $crawler = $client->request('POST', '/');

        $mailCollector = $client->getProfile()->getCollector('swiftmailer');

        // Check that an email was sent
        $this->assertEquals(1, $mailCollector->getMessageCount());

        $collectedMessages = $mailCollector->getMessages();
        $message = $collectedMessages[0];

        // Asserting email data
        $this->assertInstanceOf('Swift_Message', $message);
        $this->assertEquals('CAVE ... - Cadeau d\'anniversaire', $message->getSubject());
        $this->assertEquals('carinedelrieux@gmail.fr', key($message->getFrom()));
        $this->assertEquals('carinedelrieux@gmail.com', key($message->getTo()));
    }
}
