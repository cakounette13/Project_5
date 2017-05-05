<?php

namespace OC\UserBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\User\UserInterface;

class UserRepository extends EntityRepository
{
    /**
     * @param $username
     * @return mixed
     */
	public function findByUsernameOrEmail( $username ) {
		return $this->createQueryBuilder( 'u' )
		            ->andWhere( 'u.username = :username OR u.email = :username' )
		            ->setParameter( 'username', $username )
		            ->getQuery()
		            ->getOneOrNullResult();
	}

    /**
     * @param $email
     * @return mixed
     */
	public function findByEmail($email)
	{
		return $this->createQueryBuilder('u')
	        ->andWhere('u.email = :email')
	        ->setParameter('username', $email)
	        ->getQuery()
	        ->getOneOrNullResult();
	}

    /**
     * @param $role
     * @return mixed
     */
	public function findByRole($role)
	{
		return $this->createQueryBuilder('u')
            ->where('u.role = :role')
            ->setParameter('role', $role)
            ->getQuery()
            ->getOneOrNullResult();
	}
}
