<?php

namespace OC\FideliteBundle\Services;

use OC\FideliteBundle\Entity\Client;

class Email
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * Email constructor.
     *
     * @param \Swift_Mailer $mailer
     * @param \Twig_Environment $twig
     */
    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    /**
     * Envoi d'un email pour l'anniversaire
     * @param Client $client
     */
    public function envoiMail(Client $client)
    {
        $message = new \Swift_Message();

        // Composition du message du mail
        $message
            ->setCharset('UTF-8')
            ->setSubject('Cadeau d\'anniversaire')
            ->setBody($this->twig->render('OCFideliteBundle:Email:email.html.twig', array(
                'client' => $client,
            )))
            ->setContentType('text/html')
            ->setTo($client->getEmail())
            ->setFrom(array('carinedelrieux@gmail.fr' => 'Fidélité'));

        // Envoi du message au visiteur
        $this->mailer->send($message);
    }
}
