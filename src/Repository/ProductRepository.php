<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\ProductCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findProductsByString(string $str, int $max = 0)
    {
        $query = $this->createQueryBuilder('p')
            ->andWhere('p.name like :str')
            ->andWhere('p.visible = 1')
            ->setParameter('str', '%'.$str.'%')
        ;
        if ($max != 0) {
            $query->setMaxResults($max);
        }
        return $query->getQuery()
            ->getResult()
            ;
    }

    public function findByCatogory(ProductCategory $productCategory)
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.category', 'c', 'p.category = c.id')
            ->andWhere('c.id = :id')
            ->andWhere('p.visible = 1')
            ->setParameter('id', $productCategory->getId())
            ->getQuery()
            ->getResult()
            ;
    }

    public function findByCatogoryAndByReleaseDate(string $categoryName)
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.category', 'c', 'p.category = c.id')
            ->andWhere('c.name = :name')
            ->andWhere('p.visible = 1')
            ->setParameter('name', $categoryName)
            ->orderBy('p.releaseDate', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult()
            ;
    }

    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
