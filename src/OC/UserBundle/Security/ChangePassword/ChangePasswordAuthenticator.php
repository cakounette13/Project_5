<?php

namespace OC\UserBundle\Security\ChangePassword;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Guard\Token\PostAuthenticationGuardToken;
use OC\UserBundle\Entity\User;
use OC\UserBundle\Form\ChangePassword;

class ChangePasswordAuthenticator extends AbstractGuardAuthenticator {

	/**
	 * @var FormFactory
	 */
	private $formFactory;
	/**
	 * @var EntityManager
	 */
	private $em;
	/**
	 * @var RouterInterface
	 */
	private $router;
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

	public function __construct(FormFactory $formFactory, EntityManager $em, RouterInterface $router, UserPasswordEncoder $passwordEncoder, TokenStorage $token, Session $session) {
		$this->formFactory = $formFactory;
		$this->em = $em;
		$this->router = $router;
		$this->passwordEncoder = $passwordEncoder;
		$this->token = $token;
		$this->session = $session;
	}

	public function getCredentials( Request $request ) {
		$form = $this->formFactory->create(ChangePassword::class);
		$form->handleRequest($request);
		if ($request->getPathInfo() != '/profil/changer_de_mot_passe')
		{
			return null;
		}
		if ($form->isValid())
		{
			$data             = $form->getData();
			$username         = $this->token->getToken()->getUser()->getUsername();
			$oldPlainPassword = $data['oldPlainPassword'];
			$newPlainPassword = $data['newPlainPassword'];

			return [
				'username'         => $username,
				'oldPlainPassword' => $oldPlainPassword,
				'newPlainPassword' => $newPlainPassword
			];
		}
		return null;
	}

	public function getUser( $credentials, UserProviderInterface $userProvider ) {
		$username = $credentials['username'];
		return $this->em->getRepository('UserBundle:User')->findByUsernameOrEmail($username);
	}

	public function checkCredentials( $credentials, UserInterface $user ) {
		$oldPlainPassword = $credentials['oldPlainPassword'];
		$newPlainPassword = $credentials['newPlainPassword'];
		dump($newPlainPassword);
		if ($this->passwordEncoder->isPasswordValid($user, $oldPlainPassword))
		{
			$userToUpdate = $this->em->getRepository('UserBundle:User')->findByUsernameOrEmail($credentials['username']);
			$userToUpdate->setPlainPassword( $newPlainPassword );
			$this->em->persist( $userToUpdate);
			$this->em->flush();
			$this->session->getFlashBag()->add('success', 'Votre nouveau mot de passe est enregistre');
			return $this->passwordEncoder->isPasswordValid($user, $newPlainPassword);
		}
		$this->session->getFlashBag()->add('error', 'Il semblerai que vous ne pouvez pas modifier votre mot de passe sans connaitre l\'ancien');
		return true;
	}

	public function onAuthenticationFailure( Request $request, AuthenticationException $exception )
	{
		$url = $this->router->generate('security_change_password');
		return new RedirectResponse($url);
	}

	public function onAuthenticationSuccess( Request $request, TokenInterface $token, $providerKey )
	{
		dump($this->session->getFlashBag()->get('error'));
		dump($this->session->getFlashBag()->get('success'));
		dump($this->session->getFlashBag()->has('error'));
		dump($this->session->getFlashBag()->keys(['success']));
		$succes = $this->session->getFlashBag()->get('success');

		if(!is_null($succes))
		{
			$url = $this->router->generate('security_profile');
			return new RedirectResponse($url);
		}
		$url = $this->router->generate('security_change_password');
		return new RedirectResponse($url);

	}

	public function supportsRememberMe() {
		return true;
	}

	public function start( Request $request, AuthenticationException $authException = null ) {
	}

}