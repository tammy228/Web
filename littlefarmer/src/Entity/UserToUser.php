<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserToUserRepository")
 */
class UserToUser
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

    /***
     *        ______ __ __    __  __
     *       / ____// //_/   / / / /_____ ___   _____
     *      / /_   / ,<     / / / // ___// _ \ / ___/
     *     / __/  / /| |   / /_/ /(__  )/  __// /
     *    /_/    /_/ |_|   \____//____/ \___//_/
     *
     */

    /**
     * @var User $user
     * @ORM\ManyToOne(targetEntity="User", inversedBy="farmers")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var User $farmer
     * @ORM\ManyToOne(targetEntity="User", inversedBy="followers")
     * @ORM\JoinColumn(name="farmer_id", referencedColumnName="id")
     */
    private $farmer;

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

    /**
     * @return User|null
     */
    public function getUser() : ?User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return UserToUser
     */
    public function setUser(User $user) : self
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return User|null
     */
    public function getFarmer() : ?User
    {
        return $this->farmer;
    }

    /**
     * @param User $farmer
     * @return UserToUser
     */
    public function setFarmer(User $farmer) : self
    {
        $this->farmer = $farmer;
        return $this;
    }




}
