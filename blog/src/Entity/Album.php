<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AlbumRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Album
{
    public function __construct()
    {
        $this->photos = new ArrayCollection();
    }
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var bool $deletable
     * @ORM\Column(type="boolean", options={"default": true})
     */
    private $deletable = true;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

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
     * @ORM\ManyToMany(targetEntity="Photo", mappedBy="albums", cascade={"persist"})
     *
     */
    private $photos;


    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function isDeletable() : bool
    {
        return $this->deletable;
    }

    /**
     * @param bool $isDeletable
     * @return Album
     */
    public function setDeletable(bool $isDeletable) : self
    {
        $this->deletable = $isDeletable;
        return $this;
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

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt;
    }

    /**
     * @return Album
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
     * @return Album
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
    /**
     * @param \DateTime $offlineAt
     * @return Album
     */
    public function setOfflineAt(\DateTime $offlineAt): self
    {
        $this->offlineAt = $offlineAt;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getPhotos()
    {
        return $this->photos;
    }

    /**
     * @param Photo $photos
     */
    public function addPhotos(Photo $photos)
    {
        $photos->addAlbums($this);
        $this->photos[] = $photos;
    }
}
