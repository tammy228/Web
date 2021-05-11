<?php

namespace App\Repository;

use App\Entity\UserEmailVerifyToken;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserEmailVerifyToken|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserEmailVerifyToken|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserEmailVerifyToken[]    findAll()
 * @method UserEmailVerifyToken[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserEmailVerifyTokenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserEmailVerifyToken::class);
    }

    // /**
    //  * @return UserEmailVerifyToken[] Returns an array of UserEmailVerifyToken objects
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
    public function findOneBySomeField($value): ?UserEmailVerifyToken
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
