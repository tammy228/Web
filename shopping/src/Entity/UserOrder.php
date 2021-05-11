<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderRepository")
 */
class UserOrder
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="array")
     */
    private $product = [];

    /**
     * @ORM\Column(type="array")
     */
    private $format = [];

    /**
     * @ORM\Column(type="array")
     */
    private $quantity = [];

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="order")
     * @ORM\JoinColumn(name="user_order", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $TradeNo;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $productId = [];

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?array
    {
        return $this->product;
    }

    public function setProduct(array $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getFormat(): ?array
    {
        return $this->format;
    }

    public function setFormat(array $format): self
    {
        $this->format = $format;

        return $this;
    }

    public function getQuantity(): ?array
    {
        return $this->quantity;
    }

    public function setQuantity(array $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getTradeNo(): ?string
    {
        return $this->TradeNo;
    }

    public function setTradeNo(string $TradeNo): self
    {
        $this->TradeNo = $TradeNo;

        return $this;
    }

    public function getProductId(): ?array
    {
        return $this->productId;
    }

    public function setProductId(?array $productId): self
    {
        $this->productId = $productId;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }
}
