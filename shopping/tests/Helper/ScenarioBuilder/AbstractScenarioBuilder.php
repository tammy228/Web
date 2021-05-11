<?php
/**
 * Created by PhpStorm.
 * User: floatflower
 * Date: 2020/2/14
 * Time: 10:27 PM
 */

namespace App\Tests\Helper\ScenarioBuilder;

use Doctrine\ORM\EntityManagerInterface;

abstract class AbstractScenarioBuilder
{
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function build() {}
}