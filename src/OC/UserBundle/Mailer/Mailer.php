<?php

namespace OC\UserBundle\Mailer;

class Mailer
{
	/**
	 * @var \Twig_Environment
	 */
	private $twig;

	/**
	 * @var \Swift_Mailer
	 */
	private $transport;

    /**
     * Mailer constructor.
     * @param \Swift_Mailer $transport
     * @param \Twig_Environment $twig
     */
	public function __construct(\Swift_Mailer $transport, \Twig_Environment $twig)
	{
		$this->twig = $twig;
		$this->transport = $transport;
	}

    /**
     * @param $email
     * @param $token
     */
	public function sendEmailForgePassword($email, $token)
	{
		$message =  \Swift_Message::newInstance();
		$message->setSubject('Mot de passe Utilisateur')
			->setFrom('carinedelrieux@gmail.com')
			->setTo($email)
			->setBody(
				$this->twig->render('OCUserBundle:Emails:resetPassword.html.twig',
					[ 'token' => $token ]),
				'text/html'
			)
		;
		$this->transport->send($message);
	}
}
