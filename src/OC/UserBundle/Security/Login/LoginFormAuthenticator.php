<?php

namespace OC\UserBundle\Security\Login;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Security;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Routing\RouterInterface;
use OC\UserBundle\Form\LoginForm;

class LoginFormAuthenticator extends AbstractGuardAuthenticator
{
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
     * LoginFormAuthenticator constructor.
     * @param FormFactory $formFactory
     * @param EntityManager $em
     * @param RouterInterface $router
     * @param UserPasswordEncoder $passwordEncoder
     */
	public function __construct(FormFactory $formFactory, EntityManager $em, RouterInterface $router, UserPasswordEncoder $passwordEncoder) {
		$this->formFactory = $formFactory;
		$this->em = $em;
		$this->router = $router;
		$this->passwordEncoder = $passwordEncoder;
	}

	public function getCredentials(Request $request)
	{
		$form = $this->formFactory->create(LoginForm::class);
		$form->handleRequest($request);
		if ($request->getPathInfo() != '/login' || !$form->isSubmitted()) {
			return null;
		}
		$data = $form->getData();
		$username = $data['_username'];
		$password = $data['_password'];
		return [
			'username' => $username,
			'password' => $password
		];
	}

	public function getUser($credentials, UserProviderInterface $userProvider)
	{
		$username = $credentials['username'];
		return $this->em->getRepository('OCUserBundle:User')->findByUsernameOrEmail($username);
	}

	public function checkCredentials($credentials, UserInterface $user)
	{
		$plainPassword = $credentials['password'];
		if (!$this->passwordEncoder->isPasswordValid($user, $plainPassword)) {
			throw new BadCredentialsException();
		}
		return $this->passwordEncoder->isPasswordValid($user, $plainPassword);
	}

	public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
	{
		$request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);
		$url = $this->router->generate('security_login');
		return new RedirectResponse($url);

	}
	public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
	{
		$url = $this->router->generate('accueil');
		return new RedirectResponse($url);
	}

	public function start( Request $request, AuthenticationException $authException = null )
	{
		$url = $this->router->generate('security_login');
		return new RedirectResponse($url);
	}

	public function supportsRememberMe()
	{
		return true;
	}
}
