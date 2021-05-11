<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ChatRoomRepository")
 */
class ChatRoom
{
    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
        $this->messages = new \Doctrine\Common\Collections\ArrayCollection();

    }

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="chatRooms")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity="Message", mappedBy="chatRoom", cascade={"remove"})
     */
    private $messages;

    /**
     * @ORM\OneToMany(targetEntity="UserToUser", mappedBy="rooms")
     */
    private $friend;

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

    /**
     * @return ArrayCollection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @return ArrayCollection
     */
    public function getMessages()
    {
        return $this->messages;
    }
    /**
     * @param User $users
     */
    public function addUsers(User $users)
    {
        $users->addChatRooms($this);
        $this->users[] = $users;
    }

    public function removeUsers(User $users)
    {
        $this->users->removeElement($users) ;
    }
}
