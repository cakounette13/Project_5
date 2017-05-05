<?php

namespace OC\UserBundle\Security\Register;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use OC\UserBundle\Entity\User;
use OC\UserBundle\Form\UserRegistrationForm;
use OC\UserBundle\Security\Login\LoginFormAuthenticator;

class RegisterFormAuthenticator
{
	/**
	 * @var FormFactory
	 */
	private $form;

	/**
	 * @var EntityManager
	 */
	private $em;

	/**
	 * @var Session
	 */
	private $session;

	/**
	 * @var GuardAuthenticatorHandler
	 */
	private $handler;

	/**
	 * @var LoginFormAuthenticator
	 */
	private $authenticator;

    /**
     * RegisterFormAuthenticator constructor.
     * @param FormFactory $form
     * @param EntityManager $em
     * @param Session $session
     * @param GuardAuthenticatorHandler $handler
     * @param LoginFormAuthenticator $authenticator
     */
	public function __construct(FormFactory $form, EntityManager $em, Session $session, GuardAuthenticatorHandler $handler, LoginFormAuthenticator $authenticator)
	{
		$this->form = $form;
		$this->em = $em;
		$this->session = $session;
		$this->handler = $handler;
		$this->authenticator = $authenticator;
	}

	public function registerForm(Request $request)
	{
		$user = new User();
		$registrationForm = $this->form->create(UserRegistrationForm::class, $user);
		$registrationForm->handleRequest($request);
		if ($registrationForm->isValid())
		{
			$registrationForm->getData();
			$user->setRoles(['ROLE_USER']);
			$this->em->persist($user);
			$this->em->flush();
			$this->handler->authenticateUserAndHandleSuccess(
				$user,
				$request,
				$this->authenticator,
				'main'
			);
			$this->session->getFlashBag()->add('success', 'Bienvenue '. $user->getUsername() .' !');
		}
		return $registrationForm->createView();
	}
}
