<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Category
{
    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->product = new ArrayCollection();
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
    private $thumbnail;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updateAt;


    /***
     *        ______ __ __    ______        __
     *       / ____// //_/   / ____/____ _ / /_ ___   ____ _ ____   _____ __  __
     *      / /_   / ,<     / /    / __ `// __// _ \ / __ `// __ \ / ___// / / /
     *     / __/  / /| |   / /___ / /_/ // /_ /  __// /_/ // /_/ // /   / /_/ /
     *    /_/    /_/ |_|   \____/ \__,_/ \__/ \___/ \__, / \____//_/    \__, /
     *                                             /____/              /____/
     */

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $parent;

    /**
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent")
     */
    protected $children;

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
     * @ORM\OneToMany(targetEntity="ProductToCategory", mappedBy="category")
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

    public function getThumbnail(): ?string
    {
        return $this->thumbnail;
    }

    public function setThumbnail(string $thumbnail): self
    {
        $this->thumbnail = $thumbnail;
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

    public function getParent()
    {
        return $this->parent;
    }

    public function setParent($parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    public function getChildren()
    {
        return $this->children;
    }

    /***
     *                    __   _
     *      ____ _ _____ / /_ (_)____   ____
     *     / __ `// ___// __// // __ \ / __ \
     *    / /_/ // /__ / /_ / // /_/ // / / /
     *    \__,_/ \___/ \__//_/ \____//_/ /_/
     *
     */

    /**
     * @param Category $category
     */
    public function addChild(Category $category)
    {
        $this->children[] = $category;
        $category->setParent($this);
    }
}
