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
use App\Repository\StoryTextRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: StoryTextRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get' => [
            'normalization_context' => ['groups' => ['storyTexts:read']]
        ],
    ],
    itemOperations: [
        'get' => [

        ],
        'put' => [
            'security' => "object.getStory().getUser() == user || is_granted('ROLE_ADMIN')",
        ],
        'delete' => [
            'controller' => DeleteAction::class,
            'security' => "object.getStory().getUser() == user || is_granted('ROLE_ADMIN')",
        ],
    ],
    denormalizationContext: ['groups' => ['story:write']],
    normalizationContext: ['groups' => ['story:read', 'stories:read']],
)]
#[ApiFilter(OrderFilter::class, properties: ['id', 'createdAt', 'updatedAt', 'email'])]
#[ApiFilter(SearchFilter::class, properties: ['id' => 'exact', 'post' => 'exact'])]
class StoryText implements
    CreatedAtSettableInterface,
    UpdatedAtSettableInterface,
    IsDeletedSettableInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['storyTexts:read'])]
    private $id;

    #[ORM\ManyToOne(targetEntity: Story::class, inversedBy: 'storyTexts')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['storyTexts:read', 'storyText:write'])]
    private $story;

    #[ORM\Column(type: 'text')]
    #[Groups(['storyTexts:read', 'storyText:write'])]
    private $text;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['storyTexts:read'])]
    private $createdAt;

    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Groups(['storyTexts:read'])]
    private $updatedAt;

    #[ORM\Column(type: 'boolean')]
    private $isDeleted = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStory(): ?Story
    {
        return $this->story;
    }

    public function setStory(?Story $story): self
    {
        $this->story = $story;

        return $this;
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
}
