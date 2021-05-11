<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

abstract class SearchRepository extends ServiceEntityRepository
{
    /***
     *        ______                     __   _
     *       / ____/__  __ ____   _____ / /_ (_)____   ____
     *      / /_   / / / // __ \ / ___// __// // __ \ / __ \
     *     / __/  / /_/ // / / // /__ / /_ / // /_/ // / / /
     *    /_/     \__,_//_/ /_/ \___/ \__//_/ \____//_/ /_/
     *
     */

    /**
     * @param string $searchKey
     * @param array $scopes
     * @param array|null $criteria
     * @param array|null $orderBy
     * @param int $limit
     * @param int $offset
     * @return mixed
     */
    public function search(string $searchKey,
                           array $scopes,
                           array $criteria = null,
                           array $orderBy = null,
                           int $limit = null,
                           int $offset = null)
    {
        $qb = $this->createQueryBuilder('o');

        $searchKeys = explode(" ", $searchKey);

        if(count($scopes) > 0 && strlen($searchKey) > 0) {

            foreach ($scopes as $scope) {
                foreach ($searchKeys as $key) {
                    $uniqueParameter = "searchKey".uniqid();
                    $qb->orWhere("o.${scope} LIKE :".$uniqueParameter);
                    $qb->setParameter($uniqueParameter, '%'.$key.'%');
                }
            }
        }

        if(!is_null($criteria)) {
            foreach ($criteria as $index => $item) {
                $qb->andWhere("o.${index} = :value")->setParameter("value", $item);
            }
        }

        if(!is_null($orderBy)) {
            foreach ($orderBy as $index => $item) {
                $qb->orderBy("o.${index}", $item);
            }
        }

        if(!is_null($limit)) $qb->setMaxResults($limit);
        if(!is_null($offset)) $qb->setFirstResult($offset);

        return $qb->getQuery()->getResult();
    }
}