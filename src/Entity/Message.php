<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\MessageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
#[ApiResource]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Chat::class, inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable: false)]
    private $chat;

    #[ORM\Column(type: 'smallint')]
    private $type;

    #[ORM\OneToMany(mappedBy: 'message', targetEntity: MediaMessage::class)]
    private $mediaMessages;

    #[ORM\OneToMany(mappedBy: 'message', targetEntity: TextMessage::class)]
    private $textMessages;

    public function __construct()
    {
        $this->mediaMessages = new ArrayCollection();
        $this->textMessages = new ArrayCollection();
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
