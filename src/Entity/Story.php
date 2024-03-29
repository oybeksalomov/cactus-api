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
use App\Repository\StoryRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: StoryRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get' => [
            'normalization_context' => ['groups' => ['stories:read']]
        ],
        'post' => [
        ],
    ],
    itemOperations: [
        'get' => [
        ],
        'put' => [
            'security' => "object.getUser() == user || is_granted('ROLE_ADMIN')",
        ],
        'delete' => [
            'controller' => DeleteAction::class,
            'security' => "object.getUser() == user || is_granted('ROLE_ADMIN')",
        ],
    ],
    denormalizationContext: ['groups' => ['story:write']],
    normalizationContext: ['groups' => ['story:read', 'stories:read']],
)]
#[ApiFilter(OrderFilter::class, properties: ['id', 'createdAt', 'updatedAt', 'email'])]
#[ApiFilter(SearchFilter::class, properties: ['id' => 'exact', 'post' => 'exact'])]
class Story implements
    UserSettableInterface,
    CreatedAtSettableInterface,
    UpdatedAtSettableInterface,
    IsDeletedSettableInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['stories:read'])]
    private $id;

    #[ORM\ManyToOne(targetEntity: MediaObject::class)]
    #[Groups(['stories:read', 'story:write'])]
    private $media;

    #[ORM\Column(type: 'string', length: 6)]
    #[Groups(['stories:read', 'story:write'])]
    private $bgColor;

    #[ORM\OneToMany(mappedBy: 'story', targetEntity: StoryText::class)]
    #[Groups(['stories:read', 'story:write'])]
    private $storyTexts;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['stories:read', 'story:write'])]
    private $user;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['stories:read'])]
    private $createdAt;

    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Groups(['stories:read'])]
    private $updatedAt;

    #[ORM\Column(type: 'boolean')]
    private $isDeleted = false;

    public function __construct()
    {
        $this->storyTexts = new ArrayCollection();
    }

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

    public function getBgColor(): ?string
    {
        return $this->bgColor;
    }

    public function setBgColor(string $bgColor): self
    {
        $this->bgColor = $bgColor;

        return $this;
    }

    /**
     * @return Collection<int, StoryText>
     */
    public function getStoryTexts(): Collection
    {
        return $this->storyTexts;
    }

    public function addStoryText(StoryText $storyText): self
    {
        if (!$this->storyTexts->contains($storyText)) {
            $this->storyTexts[] = $storyText;
            $storyText->setStory($this);
        }

        return $this;
    }

    public function removeStoryText(StoryText $storyText): self
    {
        if ($this->storyTexts->removeElement($storyText)) {
            // set the owning side to null (unless already changed)
            if ($storyText->getStory() === $this) {
                $storyText->setStory(null);
            }
        }

        return $this;
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
}
