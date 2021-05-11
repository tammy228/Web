<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TextRepository")
 */
class Text
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $text;

    /**
     * @ORM\OneToOne(targetEntity="Message", mappedBy="text", cascade={"remove"})
     */
    private $message;


    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return String|null
     */
    public function getText(): ?String
    {
        return $this->text;
    }

    /**
     * @param String $text
     * @return Text
     */
    public function setText(String $text): self
    {
        $this->text = $text;
        return $this;
    }
}
