<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Controller\DeleteAction;
use App\Repository\MediaMessageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MediaMessageRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get' => [
        ],
    ],
    itemOperations: [
        'get' => [
            'security' => "object.getMessage().getUser() == user || is_granted('ROLE_ADMIN')",
        ],
        'put' => [
            'security' => "object.getMessage().getUser() == user || is_granted('ROLE_ADMIN')",
        ],
        'delete' => [
            'controller' => DeleteAction::class,
            'security' => "object.getMessage().getUser() == user || is_granted('ROLE_ADMIN')",
        ],
    ],
    denormalizationContext: ['groups' => ['mediaMessage:write']],
    normalizationContext: ['groups' => ['mediaMessage:read', 'mediaMessages:read']],
)]
#[ApiFilter(OrderFilter::class, properties: ['id'])]
#[ApiFilter(SearchFilter::class, properties: ['id' => 'exact'])]
class MediaMessage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['mediaMessages:read', 'messages:read'])]
    private $id;

    #[ORM\ManyToOne(targetEntity: MediaObject::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['mediaMessages:read', 'mediaMessage:write', 'messages:read'])]
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
