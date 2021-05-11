<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends  SearchRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    private static function handleLimit(int $limit)
    {
        return $limit === 0 ? null : $limit;
    }

    public function handleOffset(int $limit, int $page) : ?int {
        return ($page - 1) * $limit;
    }

    public function searchProduct($searchKey, int $limit = 0, int $page = 1)
    {
        return $this->search(
            $searchKey, // search key
            array("zhName"), // scope
            array(), // criteria
            array("featured" => 'DESC', 'createAt' => "DESC"), // orderBy
            self::handleLimit($limit), // limit
            self::handleOffset($limit, $page)); // offset
    }
}
