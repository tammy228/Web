<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    public function __construct() {
        $this->friendsWithMe = new \Doctrine\Common\Collections\ArrayCollection();
        $this->myFriends = new \Doctrine\Common\Collections\ArrayCollection();
        $this->chatRooms = new \Doctrine\Common\Collections\ArrayCollection();
        $this->messages = new \Doctrine\Common\Collections\ArrayCollection();
    }
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", unique=true)
     */
    private $name;

    /**
     * @var ArrayCollection|PersistentCollection $myFriends
     * @ORM\OneToMany(targetEntity="UserToUser", mappedBy="user")
     */
    private $myFriends;

    /**
     * @var ArrayCollection|PersistentCollection $friendsWithMe
     * @ORM\OneToMany(targetEntity="UserToUser", mappedBy="friend")
     */
    private $friendsWithMe;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @var ArrayCollection|PersistentCollection $chatRoom
     * @ORM\ManyToMany(targetEntity="ChatRoom", inversedBy="users", cascade={"remove"})
     * @ORM\JoinTable(name="users_to_chatrooms")
     */
    private $chatRooms;

    /**
     * @ORM\OneToMany(targetEntity="Message", mappedBy="user", cascade={"remove"})
     */
    private $messages;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $activity;

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
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
     * @return ArrayCollection
     */
    public function getFriendsWithMe()
    {
        return $this->friendsWithMe;
    }

    /**
     * @return ArrayCollection
     */
    public function getMyFriends()
    {
        return $this->myFriends;
    }

    public function addMyFriends(User $myFriends)
    {
        $this->myFriends[] = $myFriends;
    }

    public function removeMyFriends(User $myFriends)
    {
        $this->myFriends->removeElement($myFriends) ;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

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
     * @return ArrayCollection
     */
    public function getChatRooms()
    {
        return $this->chatRooms;
    }

    public function addChatRooms(ChatRoom $chatRooms)
    {
        $this->chatRooms[] = $chatRooms;
    }

    public function removeChatRooms(ChatRoom $chatRooms)
    {
        $this->chatRooms->removeElement($chatRooms) ;
    }


    public function getActivity(): ?\DateTimeInterface
    {
        return $this->activity;
    }

    /**
     * @param \DateTime $activity
     * @return User
     */
    public function setActivity(\DateTime $activity): self
    {
        $this->activity = $activity;

        return $this;
    }
}
