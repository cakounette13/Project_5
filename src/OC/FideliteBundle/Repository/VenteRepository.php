<?php

namespace OC\FideliteBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * VenteRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class VenteRepository extends EntityRepository
{
    public function getAllVentesByClient($client)
    {
        $queryBuilder = $this->createQueryBuilder('v')
            ->select('v.id', 'v.dateVente', 'v.montantVente', 'v.pointFideliteVente', 'v.pointsFideliteUtilises')
            ->where('v.client = :client')
            ->add('orderBy','v.dateVente ASC')
            ->setParameter('client', $client)
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
