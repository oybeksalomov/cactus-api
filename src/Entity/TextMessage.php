<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Controller\DeleteAction;
use App\Repository\TextMessageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TextMessageRepository::class)]
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
    denormalizationContext: ['groups' => ['textMessage:write']],
    normalizationContext: ['groups' => ['textMessage:read', 'textMessages:read']],
)]
#[ApiFilter(OrderFilter::class, properties: ['id'])]
#[ApiFilter(SearchFilter::class, properties: ['id' => 'exact'])]
class TextMessage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['messages:read'])]
    private $id;

    #[ORM\Column(type: 'text')]
    #[Groups(['messages:read'])]
    private $text;

    #[ORM\ManyToOne(targetEntity: Message::class, inversedBy: 'textMessages')]
    #[ORM\JoinColumn(nullable: false)]
    private $message;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

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
