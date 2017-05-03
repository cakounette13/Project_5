<?php

namespace OC\UserBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use OC\UserBundle\Entity\User;

class DeleteUser {
	/**
	 * @var Session
	 */
	private $session;
	/**
	 * @var EntityManager
	 */
	private $em;

	public function __construct(EntityManager $em, Session $session)
	{
		$this->session = $session;
		$this->em = $em;
	}

	public function deleteUser(Request $request)
	{
		$userId = $request->get('id');
		$userManager = $this->em->getRepository('UserBundle:User');
		$user = $userManager->findOneBy(['id' => $userId]);
		$datas = $this->em->getRepository('BirdBundle:Datas')->findByMember($userId);

        if ($datas != null) {
            return $this->session->getFlashBag()->add('validation', 'Suppression impossible car l\'utilisateur '.$user->getUsername().' est relié à une observation');
        } elseif ($user instanceof User)
		{
			$flash = $this->session->getFlashBag()->add('validation', 'Utilisateur '.$user->getUsername().' supprimé.' );
			$this->em->remove($user);
			$this->em->flush();
			return $flash;
		}
		return $this->session->getFlashBag()->add('validation', 'Mauvais utilisateur pour cette operation');
	}
}