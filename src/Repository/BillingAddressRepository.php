<?php

namespace App\Repository;

use App\Entity\BillingAddress;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BillingAddress>
 *
 * @method BillingAddress|null find($id, $lockMode = null, $lockVersion = null)
 * @method BillingAddress|null findOneBy(array $criteria, array $orderBy = null)
 * @method BillingAddress[]    findAll()
 * @method BillingAddress[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BillingAddressRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BillingAddress::class);
    }

    public function add(BillingAddress $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(BillingAddress $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findUnusedBillingAddress(): array
    {
        $sql = "SELECT ba FROM App\Entity\BillingAddress ba WHERE NOT EXISTS (SELECT o FROM App\Entity\Order o WHERE o.billingAddress = ba.id)";
        $query = $this->getEntityManager()->createQuery($sql);
        return $query->getResult();
    }

//    /**
//     * @return BillingAddress[] Returns an array of BillingAddress objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?BillingAddress
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
