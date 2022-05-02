<?php

namespace App\Repository;

use App\Entity\GiftCodeToCustomer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GiftCodeToCustomer|null find($id, $lockMode = null, $lockVersion = null)
 * @method GiftCodeToCustomer|null findOneBy(array $criteria, array $orderBy = null)
 * @method GiftCodeToCustomer[]    findAll()
 * @method GiftCodeToCustomer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GiftCodeToCustomerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GiftCodeToCustomer::class);
    }

    // /**
    //  * @return GiftCodeToCustomer[] Returns an array of GiftCodeToCustomer objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GiftCodeToCustomer
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
