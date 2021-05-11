<?php


namespace App\Entity;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

trait UUIDTrait
{
    /**
     * @var string $uuid
     * @ORM\Column(type="string", unique=true)
     */
    protected $uuid;

    /**
     * @return null|string
     */
    public function getUuid() : ?string
    {
        return $this->uuid;
    }

    /**
     * @param string $uuid
     * @return UUIDTrait
     */
    public function setUuid(string $uuid) : self
    {
        $this->uuid = $uuid;
        return $this;
    }

    /**
     * @return $this
     */
    public function generateUuid() : self
    {
        try {
            $this->uuid = Uuid::uuid4();
            $this->uuid = $this->uuid->toString();
        } catch (\Exception $exception) {/*Impossible */}
        return $this;
    }
}
