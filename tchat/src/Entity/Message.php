<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MessageRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Message
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="ChatRoom", inversedBy="messages")
     * @ORM\JoinColumn(name="chatroom_id", referencedColumnName="id")
     */
    private $chatRoom;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="messages")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $reader;


    /**
     * @ORM\OneToOne(targetEntity="Text", inversedBy="message")
     * @ORM\JoinColumn(name="text_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $text;

    /**
     * @ORM\OneToOne(targetEntity="Image", inversedBy="message")
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $image;

    /**
     * @var \DateTime $createAt
     * @ORM\Column(type="datetime")
     */
    private $createAt;

    /**
     * @ORM\OneToMany(targetEntity="UserToUser", mappedBy="message")
     */
    private $friend;



    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return ChatRoom|null
     */
    public function getChatRoom(): ?ChatRoom
    {
        return $this->chatRoom;
    }

    /**
     * @param ChatRoom $chatRoom
     * @return Message
     */
    public function setChatRoom(ChatRoom $chatRoom) :self
    {
        $this->chatRoom = $chatRoom;
        return $this;
    }

    /**
    * @return User|null
    */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return Message
     */
    public function setUser(User $user) :self
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getReader(): ?int
    {
        return $this->reader;
    }

    /**
     * @param int $reader
     * @return Message
     */
    public function setReader(int $reader) :self
    {
        $this->reader = $reader;
        return $this;
    }

    /**
     * @return Text|null
     */
    public function getText(): ?Text
    {
        return $this->text;
    }

    /**
     * @param Text $text
     * @return Message
     */
    public function setText(Text $text) :self
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @return Image|null
     */
    public function getImage(): ?Image
    {
        return $this->image;
    }

    /**
     * @param Image $image
     * @return Message
     */
    public function setImage(Image $image) :self
    {
        $this->image = $image;
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
     * @return Message
     * @ORM\PrePersist
     * @throws \Exception
     */
    public function setCreateAt() : self
    {
        $this->createAt = new \DateTime("now + 8 hours");
        return $this;
    }
}
