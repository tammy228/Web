<?php

namespace App\Repository;

use App\Entity\MessageOrEvaluation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method MessageOrEvaluation|null find($id, $lockMode = null, $lockVersion = null)
 * @method MessageOrEvaluation|null findOneBy(array $criteria, array $orderBy = null)
 * @method MessageOrEvaluation[]    findAll()
 * @method MessageOrEvaluation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageOrEvaluationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MessageOrEvaluation::class);
    }

    // /**
    //  * @return MessageOrEvaluation[] Returns an array of MessageOrEvaluation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MessageOrEvaluation
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
