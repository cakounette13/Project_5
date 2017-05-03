<?php
namespace OC\UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use OC\UserBundle\Entity\User;
use OC\UserBundle\Form\LoginForm;
use OC\UserBundle\Form\ResetPassword;
use OC\UserBundle\Form\UserRegistrationForm;

class SecurityController extends Controller
{
	/**
	 * @Route("/inscription", name="security_register")
	 * @Template("OCUserBundle:security/register:register.html.twig")
	 */
	public function registerAction(Request $request)
	{
		$user = new User();
		$registrationForm = $this->createForm(UserRegistrationForm::class, $user);
		$registrationForm->handleRequest($request);
		if ($registrationForm->isValid())
		{
			$registrationForm->getData();
			$user->setRoles(['ROLE_USER']);
			$em = $this->getDoctrine()->getManager();
			$em->persist($user);
			$em->flush();
			$this->addFlash('success', 'Bienvenue '. $user->getUsername() .'! Vous pouvez maintenant accéder à la base de données!');
			return $this->get('security.authentication.guard_handler')
				->authenticateUserAndHandleSuccess(
				$user,
				$request,
				$this->get('security.login_form_authentication'),
				'main'
			);
		}

		return [ 'form'  => $registrationForm->createView() ];
	}

	/**
	 * @Route("/login", name="security_login")
	 * @Template("OCUserBundle:security/login:login.html.twig")
	 */
	public function loginAction()
	{
		$authenticationUtils = $this->get('security.authentication_utils');
		$form = $this->createForm(LoginForm::class);
		$error = $authenticationUtils->getLastAuthenticationError();
		return [
			'form'  => $form->createView(),
			'error' => $error
		];
	}

	/**
	 * @Route("/login_check", name="security_login_check")
	 */
	public function loginCheckAction()
	{
	}

	/**
	 * @Route("/renouvellement_mot_de_passe", name="security_reset_password_api")
	 * @Template("OCUserBundle:security/reset:reset_password.html.twig")
	 */
	public function resetPasswordAction()
	{
		$authenticationUtils = $this->get('security.authentication_utils');
		$form = $this->createForm(ResetPassword::class);
		$error = $authenticationUtils->getLastAuthenticationError();

		return [
			'form' => $form->createView(),
			'error' => $error
		];
	}

	/**
	 * @Route("/connexion/send_reset_email", name="security_send_email")
	 * @Template("OCUserBundle:security/reset:reset_password_send_mail.html.twig")
	 */
	public function forgetPasswordSendEmailAction(Request $request)
	{
		$form = $this->get('security.reset_password_mailer_authenticator')->resetPasswordForm($request);
		return [ 'form' => $form->createView() ];
	}

	/**
	 * @Route("/profil", name="security_profile")
	 * @Template("OCUserBundle:security/profile:profile.html.twig")
	 * @Security("has_role('ROLE_USER')")
	 */
	public function profilAction()
	{
		$name = $this->get('security.token_storage')->getToken()->getUser()->getUsername();
		$user = $this->getDoctrine()->getRepository('UserBundle:User')->findOneBy(['username' => $name]);
		return [ 'user' => $user ];
	}

	/**
	 * @Route("/profil/changer_de_mot_passe", name="security_change_password")
	 * @Template("OCUserBundle:security/profile:changePassword.html.twig")
	 * @Security("has_role('ROLE_USER')")
	 */
	public function changePasswordAction(Request $request)
	{
		$form = $this->get('security.change_password')->changePassword($request);
		return [
			'form' => $form->createView(),
		];
	}

	/**
	 * @Route("/profil/changer_de_nom", name="security_edit_profile")
	 * @Template("OCUserBundle:security/profile:changeUsername.html.twig")
	 * @Security("has_role('ROLE_USER')")
	 */
	public function changeUsernameAction(Request $request)
	{
		$form = $this->get('change_username')->changeUsername($request);
		return [ 'form' => $form ];
	}
}