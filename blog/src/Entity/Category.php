<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Category
{
    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
        $this->articles = new \Doctrine\Common\Collections\ArrayCollection();
    }
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updateAt;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $parent;

    /**
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent")
     */
    protected $children;

    public function addChild(Category $category)
    {
        $this->children[] = $category;
        $category->setParent($this);
    }

    /**
     * @ORM\ManyToMany(targetEntity="Article", mappedBy="categories", cascade={"persist"})
     */
    private $articles;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt;
    }

    /**
     * @return Category
     * @ORM\PrePersist
     * @throws
     */
    public function setCreateAt(): self
    {
        $this->createAt = new \DateTime("now + 8 hours");

        return $this;
    }

    public function getUpdateAt(): ?\DateTimeInterface
    {
        return $this->updateAt;
    }

    /**
     * @return Category
     * @throws \Exception
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function setUpdateAt(): self
    {
        $this->updateAt = new \DateTime("now + 8 hours");

        return $this;
    }


    public function addArticle(Category $category)
    {
        $this->children[] = $category;
        $category->setArticle($this);
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function getChildren()
    {
        return $this->children;
    }

    public function setParent($parent): self
    {
        $this->parent = $parent;

        return $this;
    }


    /**
     * @return ArrayCollection
     */
    public function getArticles()
    {
        return $this->articles;
    }



    /**
     * @param Article $articles
     */
    public function addArticles(Article $articles)
    {
        $articles->addCategories($this);
        $this->articles[] = $articles;
    }


}
