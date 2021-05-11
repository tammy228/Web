<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReportRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Report
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
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mobile;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $content;

    /**
     * @var \DateTime $createAt
     * @ORM\Column(type="datetime")
     */
    private $createAt;




    /***
     *        ______ __ __    ____                    __              __
     *       / ____// //_/   / __ \ _____ ____   ____/ /__  __ _____ / /_
     *      / /_   / ,<     / /_/ // ___// __ \ / __  // / / // ___// __/
     *     / __/  / /| |   / ____// /   / /_/ // /_/ // /_/ // /__ / /_
     *    /_/    /_/ |_|  /_/    /_/    \____/ \__,_/ \__,_/ \___/ \__/
     *
     */

    /**
     * @ORM\OneToOne(targetEntity="Product", inversedBy="report")
     * @ORM\JoinColumn(name="report_id", referencedColumnName="id")
     */
    private $product;


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

    public function getMobile(): ?string
    {
        return $this->mobile;
    }

    public function setMobile(string $mobile): self
    {
        $this->mobile = $mobile;

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

    public function getProduct()
    {
        return $this->product ;
    }

    public function setProduct($product)
    {
        $this->product = $product;
        return $this;
    }
}
