<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductionRangeRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class ProductionRange
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
    private $enName;

    /**
     * @ORM\Column(type="string")
     */
    private $zhName;

    /**
     * @ORM\Column(type="string")
     */
    private $enDescription;

    /**
     * @ORM\Column(type="string")
     */
    private $zhDescription;

    /**
     * @ORM\Column(type="string")
     */
    private $thumbNail = "";

    /**
     * @ORM\Column(type="array")
     */
    private $images=[];

    /**
     * @ORM\Column(type="boolean")
     */
    private $showCase;

    /**
     * @ORM\Column(type="integer")
     */
    private $sort;

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

    public function getEnName(): ?string
    {
        return $this->enName;
    }

    public function setEnName(string $enName): self
    {
        $this->enName = $enName;

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

    public function getEnDescription(): ?string
    {
        return $this->enDescription;
    }

    public function setEnDescription(string $enDescription): self
    {
        $this->enDescription = $enDescription;

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

    public function getShowCase(): ?bool
    {
        return $this->showCase;
    }

    public function setShowCase(bool $showCase): self
    {
        $this->showCase = $showCase;

        return $this;
    }

    public function getSort(): ?int
    {
        return $this->sort;
    }

    public function setSort(int $sort): self
    {
        $this->sort = $sort;

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
