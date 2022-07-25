<?php

namespace App\Repository;

use App\Entity\Customer;
use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    public function findCartsNotModifiedSince(\DateTime $limitDate, int $limit = 10): array
    {
        return $this->createQueryBuilder('o')
            ->innerJoin('o.state', 's', 'o.state = state.id')
            ->andWhere('s.code = :code')
            ->andWhere('o.updatedAt < :date')
            ->setParameters([
                'date' => $limitDate,
                'code' => "pending"
            ])
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findCompleteOrdersForCustomer(Customer $customer): array
    {
        return $this->createQueryBuilder('o')
            ->innerJoin('o.state', 's', 'o.state = state.id')
            ->andWhere('s.code != :code')
            ->andWhere('o.customer = :customer')
            ->setParameters([
                'code' => 'pending',
                'customer' => $customer
            ])
            ->orderBy('o.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findUncompletedOrderWithPromoCode(Customer $customer): array
    {
        return $this->createQueryBuilder('o')
            ->innerJoin('o.state', 's', 'o.state = state.id')
            ->andWhere('o.customer = :customer')
            ->andWhere('o.promotionCode IS NOT NULL')
            ->andWhere('s.code = :pending')
            ->orWhere('s.code = :cancel')
            ->setParameters([
                'customer' => $customer->getId(),
                'pending' => "pending",
                'cancel' => "cancel"
            ])
            ->getQuery()
            ->getResult()
            ;
    }

    // /**
    //  * @return Order[] Returns an array of Order objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Order
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
