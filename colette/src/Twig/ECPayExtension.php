<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class ECPayExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('getTotalPrice', [$this, 'getTotalPrice']),
        ];
    }

    public function getTotalPrice($relations)
    {
        $total = 0;
        foreach ($relations as $relation)
        {
            $total += $relation->getProduct()->getPrice() * $relation->getQuantity() ;
        }
        return $total;
    }
}
