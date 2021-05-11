<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductToUserOrderRepository")
 */
class ProductToUserOrder
{
    /**
     * @ORM\Id
     * @var UserOrder $userOrder
     * @ORM\ManyToOne(targetEntity="UserOrder", inversedBy="products")
     * @ORM\JoinColumn(name="user_order_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $userOrder;

    /**
     * @ORM\Id
     * @var Product $product
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="userOrders")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $product;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /***
     *                     __   __
     *       ____ _ ___   / /_ / /_ ___   _____
     *      / __ `// _ \ / __// __// _ \ / ___/
     *     / /_/ //  __// /_ / /_ /  __// /
     *     \__, / \___/ \__/ \__/ \___//_/
     *    /____/          __   __
     *       _____ ___   / /_ / /_ ___   _____
     *      / ___// _ \ / __// __// _ \ / ___/
     *     (__  )/  __// /_ / /_ /  __// /
     *    /____/ \___/ \__/ \__/ \___//_/
     *
     */

    /**
     * @return Product|null
     */
    public function getProduct(): ?Product
    {
        return $this->product;
    }

    /**
     * @param Product $product
     * @return ProductToUserOrder
     */
    public function setProduct(Product $product) : self
    {
        $this->product = $product;
        return $this;
    }

    /**
     * @return UserOrder|null
     */
    public function getUserOrder(): ?Cart
    {
        return $this->userOrder;
    }

    /**
     * @param UserOrder $userOrder
     * @return ProductToUserOrder
     */
    public function setUserOrder(UserOrder $userOrder) : self
    {
        $this->userOrder = $userOrder;
        return $this;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }
}
