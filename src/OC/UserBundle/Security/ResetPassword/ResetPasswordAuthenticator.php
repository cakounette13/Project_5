<?php

namespace OC\UserBundle\Security\ResetPassword;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use OC\UserBundle\Form\ResetPassword;

class ResetPasswordAuthenticator extends AbstractGuardAuthenticator {
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
	 * @var FormFactory
	 */
	private $form;

	public function __construct( EntityManager $em, FormFactory $form, RouterInterface $router, UserPasswordEncoder $passwordEncoder) {
		$this->em = $em;
		$this->router = $router;
		$this->passwordEncoder = $passwordEncoder;
		$this->form = $form;
	}

	public function getCredentials( Request $request )
	{
		$token = $request->get('token');
		if ($request->getPathInfo() != '/renouvellement_mot_de_passe' || !$token)
		{
			return null;
		}
		$form = $this->form->create(ResetPassword::class);
		$form->handleRequest($request);
		if ($form->isValid())
		{
			$data = $form->getData();
			return [
				'token'            => $token,
				'plainPassword' => $data['plainPassword']
			];
		}
		return null;
	}

	public function getUser($credentials, UserProviderInterface $userProvider)
	{
		if ($credentials['token']) {
			$user = $this->em->getRepository( 'UserBundle:User' )
                 ->findOneBy( [ 'apiToken' => $credentials['token'] ] );
			if ( !$user )
			{
				throw new AuthenticationCredentialsNotFoundException();
			}
			$user->setPlainPassword( $credentials['plainPassword'] );
			$user->setApiToken(null);
			$this->em->persist( $user );
			$this->em->flush();
			return $user;
		}
	}

	public function checkCredentials( $credentials, UserInterface $user )
	{
		$plainPassword = $credentials['plainPassword'];
		if (!$this->passwordEncoder->isPasswordValid($user, $plainPassword)) {
			throw new BadCredentialsException();
		}
		return $this->passwordEncoder->isPasswordValid($user, $plainPassword);
	}

	public function onAuthenticationFailure( Request $request, AuthenticationException $exception )
	{
		$request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);
		$url = $this->router->generate('security_reset_password_api', ['_token' => $request->get('token') ]);
		return new RedirectResponse($url);
	}

	public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
	{
		$url = $this->router->generate('bird');
		return new RedirectResponse($url);
	}

	public function supportsRememberMe() {
		return false;
	}

	public function start( Request $request, AuthenticationException $authException = null )
	{
		$url = $this->router->generate('bird');
		return new RedirectResponse($url);
	}
}