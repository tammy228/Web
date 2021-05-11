<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use phpDocumentor\Reflection\Types\Integer;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class User implements UserInterface
{

    public function __construct() {
        $this->farmers = new \Doctrine\Common\Collections\ArrayCollection();
        $this->followers = new \Doctrine\Common\Collections\ArrayCollection();
        $this->products = new \Doctrine\Common\Collections\ArrayCollection();
        $this->userOrders = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @var string $uuid
     * @ORM\Column(type="string", unique=true)
     */
    private $uuid;

    /**
     * @var string $googleId
     * @ORM\Column(type="string", unique=true, nullable=true)
     */
    private $googleId;

    /**
     * @var string $facebookId
     * @ORM\Column(type="string", unique=true, nullable=true)
     */
    private $facebookId;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @var bool
     * @ORM\Column(type="boolean", options={"default": false})
     */
    private $emailValidated = false;

    /**
     * @var \DateTime $emailValidateAt
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $emailValidateAt;

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
     * 1 => farmer
     * 2 => user
     * @ORM\Column(type="integer")
     */
    private $roleCodes;

    /**
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $hashKey;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $hashIV;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $merchantID;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mobile;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $birthday;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sexual;

    /**
     * @ORM\Column(type="integer")
     */
    private $deleted = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private $applied = 0;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $instruction;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $farm;


    /**
     * @var \DateTime $createAt
     * @ORM\Column(type="datetime")
     */
    private $createAt;

    /***
     *        ______ __ __    __  __                  ______        __  __
     *       / ____// //_/   / / / /_____ ___   _____/_  __/____   / / / /_____ ___   _____
     *      / /_   / ,<     / / / // ___// _ \ / ___/ / /  / __ \ / / / // ___// _ \ / ___/
     *     / __/  / /| |   / /_/ /(__  )/  __// /    / /  / /_/ // /_/ /(__  )/  __// /
     *    /_/    /_/ |_|   \____//____/ \___//_/    /_/   \____/ \____//____/ \___//_/
     *
     */

    /**
     * @var ArrayCollection|PersistentCollection $farmer
     * @ORM\OneToMany(targetEntity="UserToUser", mappedBy="user")
     */
    private $farmers;

    /**
     * @var ArrayCollection|PersistentCollection $follower
     * @ORM\OneToMany(targetEntity="UserToUser", mappedBy="farmer")
     */
    private $followers;

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
     * @ORM\JoinColumn(name="userOrder_id", referencedColumnName="id")
     */
    private $userOrders;

    /**
     * @ORM\OneToMany(targetEntity="UserOrder", mappedBy="farmer")
     * @ORM\JoinColumn(name="userOrder_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $farmerOrders;


    /***
     *        ______ __ __    ____                    __              __
     *       / ____// //_/   / __ \ _____ ____   ____/ /__  __ _____ / /_
     *      / /_   / ,<     / /_/ // ___// __ \ / __  // / / // ___// __/
     *     / __/  / /| |   / ____// /   / /_/ // /_/ // /_/ // /__ / /_
     *    /_/    /_/ |_|  /_/    /_/    \____/ \__,_/ \__,_/ \___/ \__/
     *
     */

    /**
     * @ORM\OneToMany(targetEntity="Product", mappedBy="user")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $products;


    /***
     *        ______ __ __    _    __             _  ____
     *       / ____// //_/   | |  / /___   _____ (_)/ __/__  __
     *      / /_   / ,<      | | / // _ \ / ___// // /_ / / / /
     *     / __/  / /| |     | |/ //  __// /   / // __// /_/ /
     *    /_/    /_/ |_|     |___/ \___//_/   /_//_/   \__, /
     *                                                /____/
     */


    /**
     * @ORM\OneToOne(targetEntity="ForgetPasswordVerify", mappedBy="user")
     */
    private $FPVerify;


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

    public function getGoogleId() : ?string
    {
        return $this->googleId;
    }

    public function setGoogleId(string $id) : self
    {
        $this->googleId = $id;
        return $this;
    }

    public function getFacebookId() : ?string
    {
        return $this->facebookId;
    }

    public function setFacebookId(string $id) : self
    {
        $this->facebookId = $id;
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

    public function getEmailVerified()
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
        $roles = $this->roles;

        return array_unique($roles);
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
        return $this->password;
    }

    public function setPassword($password): self
    {
        $this->password = $password;

        return $this;
    }


    public function getHashKey()
    {
        return $this->hashKey;
    }

    public function setHashKey($hashKey): self
    {
        $this->hashKey = $hashKey;

        return $this;
    }


    public function getHashIV()
    {
        return $this->hashIV;
    }

    public function setHashIV($hashIV): self
    {
        $this->hashIV = $hashIV;

        return $this;
    }

    public function getMerchantID()
    {
        return $this->merchantID;
    }

    public function setMerchantID($merchantID): self
    {
        $this->merchantID = $merchantID;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    public function setMobile($mobile)
    {
        $this->mobile = $mobile;

        return $this;
    }


    public function getBirthday()
    {
        return $this->birthday;
    }

    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    public function getSexual()
    {
        return $this->sexual;
    }


    public function setSexual($sexual)
    {
        $this->sexual = $sexual;

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
    public function getInstruction()
    {
        return $this->instruction;
    }
    public function setInstruction($instruction)
    {
        $this->instruction = $instruction;

        return $this;
    }


    public function getApplied(): ?int
    {
        return $this->applied;
    }

    public function setApplied($applied): self
    {
        $this->applied = $applied;

        return $this;
    }

    public function getFarm()
    {
        return $this->farm;
    }

    public function setFarm($farm)
    {
        $this->farm = $farm;
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
     * @return ArrayCollection
     */
    public function getFarmers()
    {
        return $this->farmers;
    }

    /**
     * @return ArrayCollection
     */
    public function getFollowers()
    {
        return $this->followers;
    }


    /**
     * @return Cart|null
     */
    public function getCart(): ?Cart
    {
        return $this->cart;
    }

    /**
     * @param Cart $cart
     * @return User
     */
    public function setCart(Cart $cart) :self
    {
        $this->cart = $cart;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getUserOrders()
    {
        return $this->userOrders;
    }

    /**
     * @return ArrayCollection
     */
    public function getFarmerOrders()
    {
        return $this->farmerOrders;
    }

    /**
     * @return ArrayCollection
     */
    public function getProducts()
    {
        return $this->products;
    }

    public function getFPVerify()
    {
        return $this->FPVerify;
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

    /**
     * @param User $user
     * @return bool
     */
    public static function isFarmerUser(User $user)
    {
        return $user->getRoles() === ['ROLE_FARMER'];
    }

    /**
     * @return $this
     * @throws \Exception
     */
    public function validateEmail()
    {
        $this->emailValidated = true;
        $this->emailValidateAt = new \DateTime("now + 8 hours");
        return $this;
    }

    /**
     * @return bool
     */
    public function isEmailValidated() : bool
    {
        return $this->emailValidated;
    }


    public function addFollowers(User $followers)
    {
        $this->followers[] = $followers;
    }

    public function removeFollowers(User $followers)
    {
        $this->followers->removeElement($followers) ;
    }

    public function UserOrders(UserOrder $userOrders)
    {
        $this->userOrders[] = $userOrders;
    }

    public function addProducts(Product $products)
    {
        $this->products[] = $products;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {}

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
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {}
}
