<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NewsRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class News
{

    public function __construct()
    {
        $this->generateUuid();
    }

    use UUIDTrait;

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
     * @ORM\Column(type="string")
     */
    private $enTitle;

    /**
     * @ORM\Column(type="string")
     */
    private $zhTitle;

    /**
     * @ORM\Column(type="text")
     */
    private $enContent;

    /**
     * @ORM\Column(type="text")
     */
    private $zhContent;

    /**
     * @ORM\Column(type="string")
     */
    private $thumbNail = "";

    /**
     * @ORM\Column(type="array")
     */
    private $images = [];

    /**
     * @ORM\Column(type="datetime")
     */
    private $createAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updateAt;

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

    public function getEnTitle(): ?string
    {
        return $this->enTitle;
    }

    public function setEnTitle(string $enTitle): self
    {
        $this->enTitle = $enTitle;

        return $this;
    }

    public function getZhTitle(): ?string
    {
        return $this->zhTitle;
    }

    public function setZhTitle(string $zhTitle): self
    {
        $this->zhTitle = $zhTitle;

        return $this;
    }

    public function getEnContent(): ?string
    {
        return $this->enContent;
    }

    public function setEnContent(string $enContent): self
    {
        $this->enContent = $enContent;

        return $this;
    }

    public function getZhContent(): ?string
    {
        return $this->zhContent;
    }

    public function setZhContent(string $zhContent): self
    {
        $this->zhContent = $zhContent;

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
     * @ORM\PreUpdate()
     * @throws \Exception
     */
    public function setUpdateAt() : self
    {
        $this->updateAt = new \DateTime("now + 8 hours");
        return $this;
    }
}
