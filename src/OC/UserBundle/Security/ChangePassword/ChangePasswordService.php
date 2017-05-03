<?php

namespace OC\UserBundle\Security\ChangePassword;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use OC\UserBundle\Entity\User;
use OC\UserBundle\Form\ChangePassword;

class ChangePasswordService {

	/**
	 * @var FormFactory
	 */
	private $formFactory;
	/**
	 * @var EntityManager
	 */
	private $em;
	/**
	 * @var UserPasswordEncoder
	 */
	private $passwordEncoder;
	/**
	 * @var TokenStorage
	 */
	private $token;
	/**
	 * @var Session
	 */
	private $session;
	/**
	 * @var ValidatorInterface
	 */
	private $validator;

	public function __construct(FormFactory $formFactory, EntityManager $em, UserPasswordEncoder $passwordEncoder, TokenStorage $token, ValidatorInterface $validator, Session $session) {
		$this->formFactory = $formFactory;
		$this->em = $em;
		$this->passwordEncoder = $passwordEncoder;
		$this->token = $token;
		$this->validator = $validator;
		$this->session = $session;
	}

	public function changePassword(Request $request) {
		$form = $this->formFactory->create( ChangePassword::class);
		$form->handleRequest( $request );
		if ( $form->isValid() )
		{
			$username = $this->token->getToken()->getUser()->getUSername();
			$user = $this->em->getRepository('UserBundle:User')->findOneBy(['username' => $username]);
			$data = $form->getData();
			$user->setPlainPassword( $data['plainPassword'] );
			$this->em->persist($user);
			$this->em->flush();
			$this->session->getFlashBag()->add('success', 'Votre nouveau mot de passe est valide');
			return $form;
		}
		return $form;
	}
}