<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Id;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserToUserRepository")
 * @ORM\Table(name="users_to_users")
 */
class UserToUser
{
    /**
     * @ORM\Id()
     * @var string $uuid
     * @ORM\Column(type="string", unique=true)
     */
    private $uuid;

    /**
     * @ORM\Column(type="integer")
     */
    private $private;

    /**
     * @var User $user
     * @ORM\ManyToOne(targetEntity="User", inversedBy="myFriends")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var User $friend
     * @ORM\ManyToOne(targetEntity="User", inversedBy="friendsWithMe")
     * @ORM\JoinColumn(name="friend_user_id", referencedColumnName="id")
     */
    private $friend;

    /**
     * @ORM\ManyToOne(targetEntity="ChatRoom", inversedBy="friend")
     * @ORM\JoinColumn(name="room_id", referencedColumnName="id")
     */
    private $rooms;

    /**
     * @ORM\ManyToOne(targetEntity="Message", inversedBy="friend")
     * @ORM\JoinColumn(name="message_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $messages;

    /**
     * @return string|null
     */
    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    /**
     * @return $this
     */
    public function setUuid(): self
    {
        $this->uuid = Uuid::uuid4();
        return $this;
    }

    /**
     * @return int|null
     */
    public function getPrivate(): ?int
    {
        return $this->private;
    }

    /**
     * @param int $private
     * @return $this
     */
    public function setPrivate(int $private): self
    {
        $this->private = $private;
        return $this;
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
    public function getFriend() : ?User
    {
        return $this->friend;
    }

    /**
     * @param User $friend
     * @return UserToUser
     */
    public function setFriend(User $friend) : self
    {
        $this->friend = $friend;
        return $this;
    }

    /**
     * @return ChatRoom|null
     */
    public function getRoom(): ?ChatRoom
    {
        return $this->rooms;
    }

    /**
     * @param ChatRoom $rooms
     * @return UserToUser
     */
    public function setRoom(ChatRoom $rooms): self
    {
            $this->rooms= $rooms;
            return $this;
    }

    /**
     * @return Message|null
     */
    public function getMessage(): ?Message
    {
        return $this->messages;
    }

    /**
     * @param Message $messages
     * @return UserToUser
     */
    public function setMessage(Message $messages): self
    {
        $this->messages= $messages;
        return $this;
    }
}
