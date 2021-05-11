<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class CouponExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('filter_name', [$this, 'doSomething']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('type', [$this, 'type']),
            new TwigFunction('target', [$this, 'target']),
            new TwigFunction('number', [$this, 'number']),

        ];
    }

    public function type($key)
    {
        if($key == 0)
        {
            $msg = '折扣';
        }
        elseif($key == 1)
        {
            $msg = '折讓';
        }
        elseif($key == 2)
        {
            $msg = '指定價錢';
        }

        return $msg;
    }

    public function target($key)
    {
        if($key == 0)
        {
            $msg = '對每件商品';
        }
        elseif($key == 1)
        {
            $msg = '對訂單';
        }

        return $msg;
    }

    public function number($type, $number)
    {
        if($type == 0)
        {
            $msg = $number."%";
        }
        else
        {
            $msg = "$".$number;
        }

        return $msg;
    }
}
