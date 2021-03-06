<?php

namespace App\Repository;

use App\Entity\UserToUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method UserToUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserToUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserToUser[]    findAll()
 * @method UserToUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserToUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserToUser::class);
    }

    // /**
    //  * @return UserToUser[] Returns an array of UserToUser objects
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
    public function findOneBySomeField($value): ?UserToUser
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
