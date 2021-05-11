<?php

namespace App\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Message;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Article
{
    public function __construct()
    {
        $this->categories = new ArrayCollection();
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
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="integer")
     */
    private $visitor;

    /**
     * @ORM\Column(type="boolean")
     */
    private $draft;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updateAt;

    /**
     * @ORM\Column(type="date")
     */
    private $offlineAt;

    /**
     * @ORM\OneToMany(targetEntity="Message", mappedBy="article")
     */
    private $message;


    /**
     * @ORM\ManyToMany(targetEntity="Category", inversedBy="articles")
     * @ORM\JoinTable(name="articles_to_categories")
     */
    private $categories;

    public function addMessage(Message $message)
    {
        $this->message[] = $message;
        $message->setArticle($this);
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getVisitor(): ?int
    {
        return $this->visitor;
    }

    public function setVisitor(int $visitor): self
    {
        $this->visitor = $visitor;

        return $this;
    }

    public function getDraft(): ?bool
    {
        return $this->draft;
    }

    public function setDraft(bool $draft): self
    {
        $this->draft = $draft;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt;
    }

    /**
     * @return Article
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
     * @return Article
     * @throws \Exception
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function setUpdateAt(): self
    {
        $this->updateAt = new \DateTime("now + 8 hours");

        return $this;
    }


    public function getOfflineAt(): ?\DateTimeInterface
    {
        return $this->offlineAt;
    }

    public function setOfflineAt(\DateTimeInterface $offlineAt): self
    {
        $this->offlineAt = $offlineAt;

        return $this;
    }



    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage($message): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getCategories()
    {
        return $this->categories;
    }


    public function addCategories(Category $categories)
    {
        $this->categories[] = $categories;
    }

    public function removeCategories(Category $categories)
    {
        $this->categories->removeElement($categories) ;
    }

}
