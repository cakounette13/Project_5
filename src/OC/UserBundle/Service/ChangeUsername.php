<?php

namespace OC\UserBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use OC\UserBundle\Form\ChangeUsernameType;

class ChangeUsername
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
	 * @var TokenStorage
	 */
	private $token;
	/**
	 * @var Session
	 */
	private $session;
	/**
	 * @var RouterInterface
	 */
	private $router;

	public function __construct(FormFactory $form, EntityManager $em, TokenStorage $token, Session $session, RouterInterface $router)
	{
		$this->form = $form;
		$this->em = $em;
		$this->token = $token;
		$this->session = $session;
		$this->router = $router;
	}

	public function changeUsername(Request $request)
	{
		$user = $this->token->getToken()->getUser();
		$form = $this->form->create(ChangeUsernameType::class, $user);
		$form->handleRequest($request);
		if ($form->isValid())
		{
			$data = $form->getData();
			$this->em->persist($data);
            $this->session->getFlashBag()->add('success', 'Modification prise en compte' );
			$this->em->flush();

		}
		return $form->createView();
	}
}