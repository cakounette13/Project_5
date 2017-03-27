<?php

namespace OC\FideliteBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

/**
 * ClientRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ClientRepository extends EntityRepository
{
    public function getAllClientsParOrdre($client)
    {
        $queryBuilder = $this->createQueryBuilder('a')
            ->select('a.id', 'a.denomination','a.nom', 'a.prenom', 'a.societe', 'a.codePostal', 'a.ville', 'a.portable', 'a.dateNaissance', 'a.email', 'a.nbrVentes', 'a.pointsFidelite' )
            ->add('orderBy','a.nom ASC, a.prenom ASC, a.societe ASC ,a.dateNaissance ASC')
        ;

        try {
            $query = $queryBuilder
                ->getQuery()
                ->getResult();
        }   catch (NoResultException $e) {
            $query = 0;
        }

        return $query;
    }
}
