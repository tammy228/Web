<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserOrderRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class UserOrder
{
    /***
     *                                                __   _
     *        ____   _____ ____   ____   ___   _____ / /_ (_)___   _____
     *       / __ \ / ___// __ \ / __ \ / _ \ / ___// __// // _ \ / ___/
     *      / /_/ // /   / /_/ // /_/ //  __// /   / /_ / //  __/(__  )
     *     / .___//_/    \____// .___/ \___//_/    \__//_/ \___//____/
     *    /_/                 /_/
     */

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", unique=true)
     */
    private $uuid;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @ORM\Column(type="string")
     */
    private $delivery;

    /**
     * @ORM\Column(type="string")
     */
    private $remark;

    /**
     * @ORM\Column(type="string")
     */
    private $payment;

    /**
     * @ORM\Column(type="string")
     */
    private $merchantTradeNo;

    /**
     * @ORM\Column(type="string")
     */
    private $recipientName;

    /**
     * @ORM\Column(type="string")
     */
    private $recipientMobile;

    /**
     * @ORM\Column(type="string")
     */
    private $recipientEmail;

    /**
     * @ORM\Column(type="string")
     */
    private $recipientAddress;

    /**
     * @ORM\Column(type="integer")
     */
    private $totalPrice;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $couponMessage;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createAt;

    /***
     *        ______ __ __    __  __
     *       / ____// //_/   / / / /_____ ___   _____
     *      / /_   / ,<     / / / // ___// _ \ / ___/
     *     / __/  / /| |   / /_/ /(__  )/  __// /
     *    /_/    /_/ |_|   \____//____/ \___//_/
     *
     */

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="userOrder")
     */
    private $user;

    /***
     *        ______ __ __    ____                    __              __
     *       / ____// //_/   / __ \ _____ ____   ____/ /__  __ _____ / /_
     *      / /_   / ,<     / /_/ // ___// __ \ / __  // / / // ___// __/
     *     / __/  / /| |   / ____// /   / /_/ // /_/ // /_/ // /__ / /_
     *    /_/    /_/ |_|  /_/    /_/    \____/ \__,_/ \__,_/ \___/ \__/
     *
     */

    /**
     * @var ArrayCollection|PersistentCollection $product
     * @ORM\OneToMany(targetEntity="ProductToUserOrder", mappedBy="userOrder")
     */
    private $product;

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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(): self
    {
        $this->uuid = Uuid::uuid4();
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

    public function getDelivery(): ?string
    {
        return $this->delivery;
    }

    public function setDelivery(string $delivery): self
    {
        $this->delivery = $delivery;
        return $this;
    }

    public function getRemark(): ?string
    {
        return $this->remark;
    }

    public function setRemark(string $remark): self
    {
        $this->remark = $remark;
        return $this;
    }

    public function getPayment(): ?string
    {
        return $this->payment;
    }

    public function setPayment(string $payment): self
    {
        $this->payment = $payment;
        return $this;
    }

    public function getMerchantTradeNo(): ?string
    {
        return $this->merchantTradeNo;
    }

    public function setMerchantTradeNo(string $merchantTradeNo): self
    {
        $this->merchantTradeNo = $merchantTradeNo;
        return $this;
    }

    public function getRecipientName(): ?string
    {
        return $this->recipientName;
    }

    public function setRecipientName(string $recipientName): self
    {
        $this->recipientName = $recipientName;
        return $this;
    }

    public function getRecipientMobile(): ?string
    {
        return $this->recipientMobile;
    }

    public function setRecipientMobile(string $recipientMobile): self
    {
        $this->recipientMobile = $recipientMobile;
        return $this;
    }

    public function getRecipientEmail(): ?string
    {
        return $this->recipientEmail;
    }

    public function setRecipientEmail(string $recipientEmail): self
    {
        $this->recipientEmail = $recipientEmail;
        return $this;
    }

    public function getRecipientAddress(): ?string
    {
        return $this->recipientAddress;
    }

    public function setRecipientAddress(string $recipientAddress): self
    {
        $this->recipientAddress = $recipientAddress;
        return $this;
    }

    public function getTotalPrice(): ?int
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(int $totalPrice): self
    {
        $this->totalPrice = $totalPrice;
        return $this;
    }

    public function getCouponMessage(): ?string
    {
        return $this->couponMessage;
    }

    public function setCouponMessage(string $couponMessage): self
    {
        $this->couponMessage = $couponMessage;
        return $this;
    }

    public function getCreateAt() : ?\DateTime
    {
        return $this->createAt;
    }

    /**
     * @ORM\PrePersist
     * @throws \Exception
     */
    public function setCreateAt() : self
    {
        $this->createAt = new \DateTime("now + 8 hours");
        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser(User $user) :self
    {
        $this->user = $user;
        return $this;
    }


}
