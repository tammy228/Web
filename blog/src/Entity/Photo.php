<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PhotoRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Photo
{
    public function __construct()
    {
        $this->albums = new ArrayCollection();
    }
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\Column(type="string")
     */
    private $contentName;

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
     * @ORM\ManyToMany(targetEntity="Album", inversedBy="photos")
     * @ORM\JoinTable(name="photos_to_albums")
     */
    private $albums;



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

    public function getContentName(): ?string
    {
        return $this->contentName;
    }

    public function setContentName(string $contentName): self
    {
        $this->contentName = $contentName;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt;
    }

    /**
     * @return Photo
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
     * @return Photo
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
     * @return Photo
     */
    public function setOfflineAt(\DateTime $offlineAt): self
    {
        $this->offlineAt = $offlineAt;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getAlbums()
    {
        return $this->albums;
    }

    public function addAlbums(Album $albums)
    {
         $this->albums[] = $albums;
    }

    public function removeAlbums(Album $albums)
    {
        $this->albums->removeElement($albums) ;
    }


}
