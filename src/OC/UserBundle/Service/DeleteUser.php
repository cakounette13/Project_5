<?php

namespace OC\UserBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use OC\UserBundle\Entity\User;

class DeleteUser
{
	/**
	 * @var Session
	 */
	private $session;

	/**
	 * @var EntityManager
	 */
	private $em;

    /**
     * DeleteUser constructor.
     * @param EntityManager $em
     * @param Session $session
     */
	public function __construct(EntityManager $em, Session $session)
	{
		$this->session = $session;
		$this->em = $em;
	}

	public function deleteUser(Request $request)
	{
		$userId = $request->get('id');
		$userManager = $this->em->getRepository('OCUserBundle:User');
		$user = $userManager->findOneBy(['id' => $userId]);
		$datas = $this->em->getRepository('OCUserBundle:User')->findById($userId);

        if ($datas === null) {
            return $this->session->getFlashBag()->add('warning', 'Suppression impossible de l\'utilisateur '.$user->getUsername().'.');
        } elseif ($user instanceof User)
		{
			$flash = $this->session->getFlashBag()->add('success', 'Utilisateur '.$user->getUsername().' supprimé.' );
			$this->em->remove($user);
			$this->em->flush();
			return $flash;
		}
	}
}
