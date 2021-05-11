<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EmailVerityRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class ForgetPasswordVerify
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
    private $verify;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $expireAt;

    /***
     *        ______ __ __    __  __
     *       / ____// //_/   / / / /_____ ___   _____
     *      / /_   / ,<     / / / // ___// _ \ / ___/
     *     / __/  / /| |   / /_/ /(__  )/  __// /
     *    /_/    /_/ |_|   \____//____/ \___//_/
     *
     */

    /**
     * @ORM\OneToOne(targetEntity="User", inversedBy="FPVerify")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVerify(): ?string
    {
        return $this->verify;
    }

    public function setVerify(string $verify): self
    {
        $this->verify = $verify;

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
    public function getExpireAt() : ?\DateTime
    {
        return $this->expireAt;
    }

    /**
     * @ORM\PrePersist
     * @throws \Exception
     */
    public function setExpireAt() : self
    {
        $this->expireAt = new \DateTime("now + 8 hours 10 minutes");
        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        return $this->user = $user;
    }
}
