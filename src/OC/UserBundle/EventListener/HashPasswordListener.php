<?php

namespace OC\UserBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use OC\UserBundle\Entity\User;

class HashPasswordListener implements EventSubscriber
{
    /**
     * @var UserPasswordEncoder
     */
	private $passwordEncoder;

    /**
     * HashPasswordListener constructor.
     * @param UserPasswordEncoder $passwordEncoder
     */
	public function __construct(UserPasswordEncoder $passwordEncoder)
	{
		$this->passwordEncoder = $passwordEncoder;
	}

    /**
     * @param LifecycleEventArgs $args
     */
	public function prePersist(LifecycleEventArgs $args) {
		$entity = $args->getEntity();
		if ( ! $entity instanceof User )return;
		$encoded = $this->passwordEncoder->encodePassword(
			$entity,
			$entity->getPlainPassword()
		);
		$entity->setPassword($encoded);
	}

    /**
     * @param LifecycleEventArgs $args
     */
	public function preUpdate(LifecycleEventArgs $args)
	{
		$entity = $args->getEntity();
		if (!$entity instanceof User) {
			return;
		}
		$this->encodePassword($entity);
		$em = $args->getEntityManager();
		$meta = $em->getClassMetadata(get_class($entity));
		$em->getUnitOfWork()->recomputeSingleEntityChangeSet($meta, $entity);
	}

    /**
     * @return array
     */
	public function getSubscribedEvents()
	{
		return ['prePersist', 'preUpdate'];
	}

	/**
	 * @param User $entity
	 */
	private function encodePassword(User $entity)
	{
		if (!$entity->getPlainPassword()) {
			return;
		}
		$encoded = $this->passwordEncoder->encodePassword(
			$entity,
			$entity->getPlainPassword()
		);
		$entity->setPassword($encoded);
	}
}
