<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Controller\DeleteAction;
use App\Entity\Interfaces\CreatedAtSettableInterface;
use App\Entity\Interfaces\IsDeletedSettableInterface;
use App\Entity\Interfaces\UpdatedAtSettableInterface;
use App\Entity\Interfaces\UserSettableInterface;
use App\Repository\MessageRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get' => [
            'normalization_context' => ['groups' => ['messages:read']]
        ],
        'post' => [
            // todo: controller
        ]
    ],
    itemOperations: [
        'get' => [
            'security' => "object.getUser() == user || object.getChat().getWithUser() == user || is_granted('ROLE_ADMIN')",
        ],
        'put' => [
            'security' => "object.getUser() == user || is_granted('ROLE_ADMIN')",
        ],
        'delete' => [
            'controller' => DeleteAction::class,
            'security' => "object.getUser() == user || is_granted('ROLE_ADMIN')",
        ],
    ],
    denormalizationContext: ['groups' => ['message:write']],
    normalizationContext: ['groups' => ['message:read', 'messages:read']],
)]
#[ApiFilter(OrderFilter::class, properties: ['id', 'createdAt', 'updatedAt', 'email'])]
#[ApiFilter(SearchFilter::class, properties: ['id' => 'exact', 'post' => 'exact'])]
class Message implements
    UserSettableInterface,
    CreatedAtSettableInterface,
    UpdatedAtSettableInterface,
    IsDeletedSettableInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Chat::class, inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['messages:read', 'message:write'])]
    private $chat;

    #[ORM\Column(type: 'smallint')]
    #[Groups(['messages:read', 'message:write'])]
    private $type;

    #[ORM\OneToMany(mappedBy: 'message', targetEntity: MediaMessage::class)]
    #[Groups(['messages:read', 'message:write'])]
    private $mediaMessages;

    #[ORM\OneToMany(mappedBy: 'message', targetEntity: TextMessage::class)]
    #[Groups(['messages:read', 'message:write'])]
    private $textMessages;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['messages:read'])]
    private $user;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['messages:read'])]
    private $createdAt;

    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Groups(['messages:read'])]
    private $updatedAt;

    #[ORM\Column(type: 'boolean')]
    private $isDeleted = false;

    public function __construct()
    {
        $this->mediaMessages = new ArrayCollection();
        $this->textMessages = new ArrayCollection();
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?UserInterface $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(bool $isDeleted): self
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChat(): ?Chat
    {
        return $this->chat;
    }

    public function setChat(?Chat $chat): self
    {
        $this->chat = $chat;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, MediaMessage>
     */
    public function getMediaMessages(): Collection
    {
        return $this->mediaMessages;
    }

    public function addMediaMessage(MediaMessage $mediaMessage): self
    {
        if (!$this->mediaMessages->contains($mediaMessage)) {
            $this->mediaMessages[] = $mediaMessage;
            $mediaMessage->setMessage($this);
        }

        return $this;
    }

    public function removeMediaMessage(MediaMessage $mediaMessage): self
    {
        if ($this->mediaMessages->removeElement($mediaMessage)) {
            // set the owning side to null (unless already changed)
            if ($mediaMessage->getMessage() === $this) {
                $mediaMessage->setMessage(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TextMessage>
     */
    public function getTextMessages(): Collection
    {
        return $this->textMessages;
    }

    public function addTextMessage(TextMessage $textMessage): self
    {
        if (!$this->textMessages->contains($textMessage)) {
            $this->textMessages[] = $textMessage;
            $textMessage->setMessage($this);
        }

        return $this;
    }

    public function removeTextMessage(TextMessage $textMessage): self
    {
        if ($this->textMessages->removeElement($textMessage)) {
            // set the owning side to null (unless already changed)
            if ($textMessage->getMessage() === $this) {
                $textMessage->setMessage(null);
            }
        }

        return $this;
    }
}
