<?php
namespace OC\UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends Controller
{
	/**
	 * @Route("/administration", name="admin")
	 * @Template("OCUserBundle:administration/administration.html.twig")
	 * @Security("has_role('ROLE_ADMIN')")
	 */
	public function usersAction()
	{
		$users = $this->getDoctrine()->getRepository('OCUserBundle:User')->findAll();
		return [ 'users' => $users ];
	}

	/**
	 * @param Request $request
	 * @Route("/utilisateur/supprimer", name="delete_user")
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function deleteUser(Request $request){
		$user = $this->get('delete_user')->deleteUser($request);
		return $this->redirectToRoute('admin', ['result', $user]);
	}
}
