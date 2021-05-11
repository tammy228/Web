<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserEmailVerifyTokenRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class UserEmailVerifyToken
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $expireAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $token;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?string
    {
        return $this->user;
    }

    public function setUser(string $user): self
    {
        $this->user = $user;

        return $this;
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

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken($token)
    {
         $this->token = $token;
         return $this;
    }

    public function setVerify(string $token): self
    {
        $this->token = $token;

        return $this;
    }
}
