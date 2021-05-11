<?php

namespace App\Repository;

use App\Entity\ForgetPasswordVerify;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ForgetPasswordVerify|null find($id, $lockMode = null, $lockVersion = null)
 * @method ForgetPasswordVerify|null findOneBy(array $criteria, array $orderBy = null)
 * @method ForgetPasswordVerify[]    findAll()
 * @method ForgetPasswordVerify[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmailVerityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ForgetPasswordVerify::class);
    }

    // /**
    //  * @return EmailVerity[] Returns an array of EmailVerity objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EmailVerity
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
