<?php

namespace App\Repository;

use App\Entity\RefurbishedToy;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RefurbishedToy|null find($id, $lockMode = null, $lockVersion = null)
 * @method RefurbishedToy|null findOneBy(array $criteria, array $orderBy = null)
 * @method RefurbishedToy[]    findAll()
 * @method RefurbishedToy[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RefurbishedToyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RefurbishedToy::class);
    }

    // /**
    //  * @return RefurbishedToy[] Returns an array of RefurbishedToy objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RefurbishedToy
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
