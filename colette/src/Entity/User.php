<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class User implements UserInterface, \Serializable
{
    public function __construct() {
        $this->userOrder = new ArrayCollection();
    }

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
     * @ORM\Column(type="string", unique=true)
     */
    private $uuid;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="boolean", options={"default": false})
     */
    private $emailValidated = false;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * 0 => admin
     * 1 => user
     * @ORM\Column(type="integer")
     */
    private $roleCodes;

    /**
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mobile;

    /**
     * Ex: 台北市;信義區;110;ＸＸ路ＸＸ號
     * @ORM\Column(type="string")
     */
    private $address;

    /**
     * @ORM\Column(type="integer")
     */
    private $deleted = 0;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createAt;

    /***
     *        ______ __ __    ______              __
     *       / ____// //_/   / ____/____ _ _____ / /_
     *      / /_   / ,<     / /    / __ `// ___// __/
     *     / __/  / /| |   / /___ / /_/ // /   / /_
     *    /_/    /_/ |_|   \____/ \__,_//_/    \__/
     *
     */

    /**
     * @ORM\OneToOne(targetEntity="Cart", mappedBy="user")
     */
    private $cart;

    /***
     *        ______ __ __    __  __                   ____             __
     *       / ____// //_/   / / / /_____ ___   _____ / __ \ _____ ____/ /___   _____
     *      / /_   / ,<     / / / // ___// _ \ / ___// / / // ___// __  // _ \ / ___/
     *     / __/  / /| |   / /_/ /(__  )/  __// /   / /_/ // /   / /_/ //  __// /
     *    /_/    /_/ |_|   \____//____/ \___//_/    \____//_/    \__,_/ \___//_/
     *
     */

    /**
     * @ORM\OneToMany(targetEntity="UserOrder", mappedBy="user")
     */
    private $userOrder;


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

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(): self
    {
        $this->uuid = Uuid::uuid4();
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getEmailValidated()
    {
        return $this->emailValidated;
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

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        return array_unique($this->roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    public function getRoleCodes(): ?int
    {
        return $this->roleCodes;
    }

    public function setRoleCodes(int $roleCodes): self
    {
        $this->roleCodes = $roleCodes;
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;
        return $this;
    }

    public function getDeleted(): ?int
    {
        return $this->deleted;
    }

    public function setDeleted(int $deleted): self
    {
        $this->deleted = $deleted;
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

    public function getCart(): ?Cart
    {
        return $this->cart;
    }

    public function setCart(Cart $cart) :self
    {
        $this->cart = $cart;
        return $this;
    }

    public function getUserOrder()
    {
        return $this->userOrder;
    }


    /***
     *                    __   _
     *      ____ _ _____ / /_ (_)____   ____
     *     / __ `// ___// __// // __ \ / __ \
     *    / /_/ // /__ / /_ / // /_/ // / / /
     *    \__,_/ \___/ \__//_/ \____//_/ /_/
     *
     */

    /**
     * @throws \Exception
     */
    public function validateEmail()
    {
        $this->emailValidated = true;
        return $this;
    }

    public function isEmailValidated(): bool
    {
        return $this->emailValidated;
    }

    /***
     *                    __   _
     *      ____ _ _____ / /_ (_)____   ____
     *     / __ `// ___// __// // __ \ / __ \
     *    / /_/ // /__ / /_ / // /_/ // / / /
     *    \__,_/ \___/ \__//_/ \____//_/ /_/
     *
     */

    /**
     * @param User $user
     * @return bool
     */
    public static function isAdminUser(User $user)
    {
        return $user->getRoles() === ['ROLE_ADMIN'];
    }

    /***
     *       __  __                     ____        __               ____
     *      / / / /_____ ___   _____   /  _/____   / /_ ___   _____ / __/____ _ _____ ___
     *     / / / // ___// _ \ / ___/   / / / __ \ / __// _ \ / ___// /_ / __ `// ___// _ \
     *    / /_/ /(__  )/  __// /     _/ / / / / // /_ /  __// /   / __// /_/ // /__ /  __/
     *    \____//____/ \___//_/     /___//_/ /_/ \__/ \___//_/   /_/   \__,_/ \___/ \___/
     *
     */

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->name;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * String representation of object
     * @link https://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->uuid,
            $this->email,
            $this->emailValidated,
            $this->name,
            $this->roles,
            $this->roleCodes,
            $this->password,
            $this->mobile,
            $this->address,
            $this->deleted,
            $this->createAt

            // see section on salt below
            // $this->salt,
        ));
    }

    /**
     * Constructs the object
     * @link https://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->uuid,
            $this->email,
            $this->emailValidated,
            $this->name,
            $this->roles,
            $this->roleCodes,
            $this->password,
            $this->mobile,
            $this->address,
            $this->deleted,
            $this->createAt
            // see section on salt below
            // $this->salt
            ) = unserialize($serialized);
    }
}
