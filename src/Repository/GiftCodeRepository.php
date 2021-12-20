<?php

namespace App\Repository;

use App\Entity\GiftCode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GiftCode|null find($id, $lockMode = null, $lockVersion = null)
 * @method GiftCode|null findOneBy(array $criteria, array $orderBy = null)
 * @method GiftCode[]    findAll()
 * @method GiftCode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GiftCodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GiftCode::class);
    }

    // /**
    //  * @return GiftCode[] Returns an array of GiftCode objects
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
    public function findOneBySomeField($value): ?GiftCode
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
