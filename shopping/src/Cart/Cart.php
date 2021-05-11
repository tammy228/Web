<?php

namespace App\Cart;



class Cart
{
    private $items=[];



    public function put($item)
    {
        array_push($this->items,$item);

        return $this;
    }

    public function all()
    {
        return $this->items;
    }

    public function total()
    {

    }
}