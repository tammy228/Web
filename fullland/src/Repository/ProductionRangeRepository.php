<?php

namespace App\Repository;

use App\Entity\ProductionRange;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProductionRange|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductionRange|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductionRange[]    findAll()
 * @method ProductionRange[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductionRangeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductionRange::class);
    }

    // /**
    //  * @return ProductionRange[] Returns an array of ProductionRange objects
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
    public function findOneBySomeField($value): ?ProductionRange
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
