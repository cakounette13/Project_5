<?php

namespace OC\UserBundle\EventListener;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use OC\UserBundle\Entity\User;
use OC\UserBundle\Form\ForgetPasswordForm;
use OC\UserBundle\Mailer\Mailer;

class ResetPasswordMailer
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
	 * @var Mailer
	 */
	private $mailer;

	/**
	 * @var Session
	 */
	private $session;

    /**
     * ResetPasswordMailer constructor.
     * @param FormFactory $form
     * @param EntityManager $em
     * @param Mailer $mailer
     * @param Session $session
     */
	public function __construct(FormFactory $form, EntityManager $em, Mailer $mailer, Session $session)
	{
		$this->form = $form;
		$this->em = $em;
		$this->mailer = $mailer;
		$this->session = $session;
	}

    /**
     * @param Request $request
     * @return \Symfony\Component\Form\FormInterface
     */
	public function resetPasswordForm(Request $request)
	{
		$form = $this->form->create(ForgetPasswordForm::class);
		$form->handleRequest($request);
		if ($form->isValid())
		{
			$userToFind = $form->getData();
			$user = $this->em->getRepository('OCUserBundle:User')->findOneBy(['email' => $userToFind]);
			if ($user instanceof User)
			{
				$token = uniqid('token_', true);
				$user->setApiToken($token);
				$this->mailer->sendEmailForgePassword($user->getEmail(), $token);
				$this->session->getFlashBag()->add('success', 'Un email vous a été envoyé pour renouveler votre mot de passe' );
				$this->em->persist($user);
				$this->em->flush();
			} else {
                $this->session->getFlashBag()->add('success', 'Cet email n\'appartient pas à l\'un de nos utilisateurs' );
            }
        }
		return $form;
	}
}
