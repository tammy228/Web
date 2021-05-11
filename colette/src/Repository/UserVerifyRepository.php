<?php

namespace App\Repository;

use App\Entity\UserVerify;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserVerify|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserVerify|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserVerify[]    findAll()
 * @method UserVerify[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserVerifyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserVerify::class);
    }

    // /**
    //  * @return UserVerify[] Returns an array of UserVerify objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserVerify
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
