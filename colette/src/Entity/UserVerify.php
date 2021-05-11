<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserVerifyRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class UserVerify
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
     * @ORM\Column(type="text")
     */
    private $userId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $token;

    /**
     * 0 => forget password
     * 1 => user email
     * 2 => auth
     * @ORM\Column(type="integer")
     */
    private $verifyCode;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $expireAt;

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

    public function getUserId()
    {
        return $this->userId;
    }

    public function setUserId($userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @param $random
     * @return UserVerify
     */
    public function setToken($random)
    {
//        $random = (string)random_int(100000, 999999);
        $this->token = $random;
        return $this;
    }

    public function getVerifyCode(): ?int
    {
        return $this->verifyCode;
    }

    public function setVerifyCode(int $verifyCode)
    {
        $this->verifyCode = $verifyCode;
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
}
