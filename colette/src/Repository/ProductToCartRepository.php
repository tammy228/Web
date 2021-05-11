<?php

namespace App\Repository;

use App\Entity\ProductToCart;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProductToCart|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductToCart|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductToCart[]    findAll()
 * @method ProductToCart[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductToCartRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductToCart::class);
    }

    // /**
    //  * @return ProductToCart[] Returns an array of ProductToCart objects
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
    public function findOneBySomeField($value): ?ProductToCart
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
