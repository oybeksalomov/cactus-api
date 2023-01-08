<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\MediaMessageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MediaMessageRepository::class)]
#[ApiResource]
class MediaMessage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: MediaObject::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $media;

    #[ORM\ManyToOne(targetEntity: Message::class, inversedBy: 'mediaMessages')]
    #[ORM\JoinColumn(nullable: false)]
    private $message;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMedia(): ?MediaObject
    {
        return $this->media;
    }

    public function setMedia(?MediaObject $media): self
    {
        $this->media = $media;

        return $this;
    }

    public function getMessage(): ?Message
    {
        return $this->message;
    }

    public function setMessage(?Message $message): self
    {
        $this->message = $message;

        return $this;
    }
}
