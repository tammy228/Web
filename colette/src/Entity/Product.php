<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Exception;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Product
{
    public function __construct() {
        $this->cart = new ArrayCollection();
        $this->userOrder = new ArrayCollection();
        $this->category = new ArrayCollection();
    }
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
     * @ORM\Column(type="string")
     */
    private $zhName;

    /**
     * @ORM\Column(type="string")
     */
    private $enName;

    /**
     * @ORM\Column(type="string")
     */
    private $thumbNail = "";

    /**
     * @ORM\Column(type="array")
     */
    private $images = [];

    /**
     * @ORM\Column(type="text")
     */
    private $zhDescription;

    /**
     * @ORM\Column(type="text")
     */
    private $enDescription;

    /**
     * @ORM\Column(type="array")
     */
    private $price = [];

    /**
     * @ORM\Column(type="array")
     */
    private $stock = [];

    /**
     * @ORM\Column(type="array")
     */
    private $size = [];

    /**
     * @ORM\Column(type="string")
     */
    private $temperature;

    /**
     * @ORM\Column(type="boolean")
     */
    private $deleted = false;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updateAt;

    /***
     *        ______ __ __    ______              __
     *       / ____// //_/   / ____/____ _ _____ / /_
     *      / /_   / ,<     / /    / __ `// ___// __/
     *     / __/  / /| |   / /___ / /_/ // /   / /_
     *    /_/    /_/ |_|   \____/ \__,_//_/    \__/
     *
     */

    /**
     * @var ArrayCollection|PersistentCollection $cart
     * @ORM\OneToMany(targetEntity="ProductToCart", mappedBy="product")
     */
    private $cart;

    /***
     *        ______ __ __    __  __                   ____             __
     *       / ____// //_/   / / / /_____ ___   _____ / __ \ _____ ____/ /___   _____
     *      / /_   / ,<     / / / // ___// _ \ / ___// / / // ___// __  // _ \ / ___/
     *     / __/  / /| |   / /_/ /(__  )/  __// /   / /_/ // /   / /_/ //  __// /
     *    /_/    /_/ |_|   \____//____/ \___//_/    \____//_/    \__,_/ \___//_/
     *
     */

    /**
     * @var ArrayCollection|PersistentCollection $userOrder
     * @ORM\OneToMany(targetEntity="ProductToUserOrder", mappedBy="product")
     */
    private $userOrder;

    /***
     *        ______ __ __    ______        __
     *       / ____// //_/   / ____/____ _ / /_ ___   ____ _ ____   _____ __  __
     *      / /_   / ,<     / /    / __ `// __// _ \ / __ `// __ \ / ___// / / /
     *     / __/  / /| |   / /___ / /_/ // /_ /  __// /_/ // /_/ // /   / /_/ /
     *    /_/    /_/ |_|   \____/ \__,_/ \__/ \___/ \__, / \____//_/    \__, /
     *                                             /____/              /____/
     */

    /**
     * @var ArrayCollection|PersistentCollection $category
     * @ORM\OneToMany(targetEntity="ProductToCategory", mappedBy="product")
     */
    private $category;

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

    public function getZhName(): ?string
    {
        return $this->zhName;
    }

    public function setZhName(string $zhName): self
    {
        $this->zhName = $zhName;
        return $this;
    }

    public function getEnName(): ?string
    {
        return $this->enName;
    }

    public function setEnName(string $enName): self
    {
        $this->enName = $enName;
        return $this;
    }

    public function getThumbNail(): ?string
    {
        return $this->thumbNail;
    }

    public function setThumbNail(string $thumbNail): self
    {
        $this->thumbNail = $thumbNail;
        return $this;
    }

    public function getImages(): ?array
    {
        return $this->images;
    }

    public function setImages(array $images): self
    {
        $this->images = $images;
        return $this;
    }

    public function getZhDescription(): ?string
    {
        return $this->zhDescription;
    }

    public function setZhDescription(string $zhDescription): self
    {
        $this->zhDescription = $zhDescription;
        return $this;
    }

    public function getEnDescription(): ?string
    {
        return $this->enDescription;
    }

    public function setEnDescription(string $enDescription): self
    {
        $this->enDescription = $enDescription;
        return $this;
    }

    public function getPrice(): ?array
    {
        return $this->price;
    }

    public function setPrice(array $price): self
    {
        $this->price = $price;
        return $this;
    }

    public function getStock(): ?array
    {
        return $this->stock;
    }

    public function setStock(array $stock): self
    {
        $this->stock = $stock;
        return $this;
    }

    public function getSize(): ?array
    {
        return $this->size;
    }

    public function setSize(array $size): self
    {
        $this->size = $size;
        return $this;
    }

    public function getTemperature(): ?string
    {
        return $this->temperature;
    }

    public function setTemperature(string $temperature): self
    {
        $this->temperature = $temperature;
        return $this;
    }

    public function getDeleted()
    {
        return $this->deleted;
    }

    public function setDeleted($deleted): self
    {
        $this->deleted = $deleted;
        return $this;
    }

    public function getCreateAt() : ?\DateTime
    {
        return $this->createAt;
    }

    /**
     * @ORM\PrePersist
     * @throws Exception
     */
    public function setCreateAt() : self
    {
        $this->createAt = new \DateTime("now + 8 hours");
        return $this;
    }

    public function getUpdateAt() : ?\DateTime
    {
        return $this->updateAt;
    }

    /**
     * @ORM\PrePersist
     * @throws Exception
     */
    public function setUpdateAt() : self
    {
        $this->updateAt = new \DateTime("now + 8 hours");
        return $this;
    }

}
