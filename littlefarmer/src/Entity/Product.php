<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use phpDocumentor\Reflection\Types\Boolean;
use phpDocumentor\Reflection\Types\Integer;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Product
{
    public function __construct() {
        $this->carts = new ArrayCollection();
        $this->userOrders = new ArrayCollection();
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
     * @var string $uuid
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
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @ORM\Column(type="integer")
     */
    private $stock;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $origin;

    /**
     * @ORM\Column(type="array")
     */
    private $QRCode = [];

    /**
     * @ORM\Column(type="boolean")
     */
    private $groupBuy;

    /**
     * @ORM\Column(type="boolean")
     */
    private $onSale;

    /**
     * @ORM\Column(type="boolean")
     */
    private $expired;

    /**
     * @ORM\Column(type="string")
     */
    private $detail;

    /**
     * @var \DateTime $createAt
     * @ORM\Column(type="datetime")
     */
    private $createAt;

    /**
     * @var \DateTime $updateAt
     * @ORM\Column(type="datetime")
     */
    private $updateAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $deleted = false;

    /***
     *        ______ __ __    __  __
     *       / ____// //_/   / / / /_____ ___   _____
     *      / /_   / ,<     / / / // ___// _ \ / ___/
     *     / __/  / /| |   / /_/ /(__  )/  __// /
     *    /_/    /_/ |_|   \____//____/ \___//_/
     *
     */

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="products")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /***
     *        ______ __ __    ______              __
     *       / ____// //_/   / ____/____ _ _____ / /_
     *      / /_   / ,<     / /    / __ `// ___// __/
     *     / __/  / /| |   / /___ / /_/ // /   / /_
     *    /_/    /_/ |_|   \____/ \__,_//_/    \__/
     *
     */

    /**
     * @var ArrayCollection|PersistentCollection $carts
     * @ORM\OneToMany(targetEntity="ProductToCart", mappedBy="product")
     */
    private $carts;

    /***
     *        ______ __ __    __  __                   ____             __
     *       / ____// //_/   / / / /_____ ___   _____ / __ \ _____ ____/ /___   _____
     *      / /_   / ,<     / / / // ___// _ \ / ___// / / // ___// __  // _ \ / ___/
     *     / __/  / /| |   / /_/ /(__  )/  __// /   / /_/ // /   / /_/ //  __// /
     *    /_/    /_/ |_|   \____//____/ \___//_/    \____//_/    \__,_/ \___//_/
     *
     */

    /**
     * @var ArrayCollection|PersistentCollection $userOrders
     * @ORM\OneToMany(targetEntity="ProductToUserOrder", mappedBy="product")
     */
    private $userOrders;


    /***
     *        ______ __ __    ______        __
     *       / ____// //_/   / ____/____ _ / /_ ___   ____ _ ____   _____ __  __
     *      / /_   / ,<     / /    / __ `// __// _ \ / __ `// __ \ / ___// / / /
     *     / __/  / /| |   / /___ / /_/ // /_ /  __// /_/ // /_/ // /   / /_/ /
     *    /_/    /_/ |_|   \____/ \__,_/ \__/ \___/ \__, / \____//_/    \__, /
     *                                             /____/              /____/
     */

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="products")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;

    /***
     *        ______ __ __    ____                             __
     *       / ____// //_/   / __ \ ___   ____   ____   _____ / /_
     *      / /_   / ,<     / /_/ // _ \ / __ \ / __ \ / ___// __/
     *     / __/  / /| |   / _, _//  __// /_/ // /_/ // /   / /_
     *    /_/    /_/ |_|  /_/ |_| \___// .___/ \____//_/    \__/
     *                                /_/
     */

    /**
     * @ORM\OneToOne(targetEntity="Report", mappedBy="product")
     */
    private $report;

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

    /**
     * @return string|null
     */
    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    /**
     * @return $this
     */
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

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;
        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;
        return $this;
    }

    public function getOrigin()
    {
        return $this->origin;
    }

    public function setOrigin($origin): self
    {
        $this->origin = $origin;
        return $this;
    }


    public function getQRCode()
    {
        return $this->QRCode;
    }

    public function setQRCode(int $quantity)
    {
        $this->QRCode = array_fill(1, $quantity, 0);

        return $this;
    }

    public function editQRCode($QRCode)
    {
        $this->QRCode = $QRCode;
        return $this;
    }

    public function getGroupBuy()
    {
        return $this->groupBuy;
    }

    public function setGroupBuy(bool $groupBuy): self
    {
        $this->groupBuy = $groupBuy;
        return $this;
    }

    public function getOnSale()
    {
        return $this->onSale;
    }

    public function setOnSale(bool $onSale): self
    {
        $this->onSale = $onSale;
        return $this;
    }

    public function getExpired()
    {
        return $this->expired;
    }

    public function setExpired(bool $expired): self
    {
        $this->expired = $expired;
        return $this;
    }

    public function getDetail(): ?string
    {
        return $this->detail;
    }

    public function setDetail(string $detail): self
    {
        $this->detail = $detail;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
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

    /**
     * @return \DateTime|null
     */
    public function getUpdateAt() : ?\DateTime
    {
        return $this->updateAt;
    }

    /**
     * @ORM\PrePersist
     * @throws \Exception
     */
    public function setUpdateAt() : self
    {
        $this->updateAt = new \DateTime("now + 8 hours");
        return $this;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return Product
     */
    public function setUser(User $user) :self
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return Category|null
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @param Category $category
     * @return Product
     */
    public function setCategory(Category $category) :self
    {
        $this->category = $category;
        return $this;
    }

    public function getReport()
    {
        return $this->report;
    }

    public function setReport($report): self
    {
        $this->report = $report;
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

    /***
     *                    __   _
     *      ____ _ _____ / /_ (_)____   ____
     *     / __ `// ___// __// // __ \ / __ \
     *    / /_/ // /__ / /_ / // /_/ // / / /
     *    \__,_/ \___/ \__//_/ \____//_/ /_/
     *
     */


    public function addCategory(Category $category)
    {
        $this->category = $category;
    }

    public function removeCategory(Category $category)
    {
        $this->category->removeElement($category) ;
    }
}
