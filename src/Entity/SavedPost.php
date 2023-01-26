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
use App\Repository\SavedPostRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SavedPostRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get' => [
            'normalization_context' => ['groups' => ['savedPosts:read']]
        ],
        'post' => [
        ],
    ],
    itemOperations: [
        'get' => [
            'security' => "object.getUser() == user || is_granted('ROLE_ADMIN')",
        ],
        'put' => [
            'security' => "object.getUser() == user || is_granted('ROLE_ADMIN')",
        ],
        'delete' => [
            'controller' => DeleteAction::class,
            'security' => "is_granted('ROLE_ADMIN')",
        ],
    ],
    denormalizationContext: ['groups' => ['savedPost:write']],
    normalizationContext: ['groups' => ['savedPost:read', 'savedPosts:read']],
)]
#[ApiFilter(OrderFilter::class, properties: ['id', 'createdAt', 'updatedAt', 'email'])]
#[ApiFilter(SearchFilter::class, properties: ['id' => 'exact', 'post' => 'exact'])]
class SavedPost implements
    UserSettableInterface,
    CreatedAtSettableInterface,
    UpdatedAtSettableInterface,
    IsDeletedSettableInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['savedPosts:read'])]
    private $id;

    #[ORM\ManyToOne(targetEntity: Post::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['savedPosts:read', 'savedPost:write'])]
    private $post;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['savedPosts:read', 'savedPost:write'])]
    private $user;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['savedPosts:read'])]
    private $createdAt;

    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Groups(['savedPosts:read'])]
    private $updatedAt;

    #[ORM\Column(type: 'boolean')]
    private $isDeleted = false;

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

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): self
    {
        $this->post = $post;

        return $this;
    }
}
