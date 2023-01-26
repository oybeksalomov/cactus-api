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
use App\Repository\SubscriptionRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SubscriptionRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get' => [
            'normalization_context' => ['groups' => ['subscriptions:read']]
        ],
    ],
    itemOperations: [
        'get' => [

        ],
        'delete' => [
            'security' => "object.getStory().getUser() == user || is_granted('ROLE_ADMIN')",
        ],
    ],
    denormalizationContext: ['groups' => ['subscription:write']],
    normalizationContext: ['groups' => ['subscription:read', 'subscriptions:read']],
)]
#[ApiFilter(OrderFilter::class, properties: ['id', 'createdAt', 'updatedAt', 'email'])]
#[ApiFilter(SearchFilter::class, properties: ['id' => 'exact', 'post' => 'exact'])]
class Subscription implements
    UserSettableInterface,
    CreatedAtSettableInterface,
    UpdatedAtSettableInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['subscriptions:read'])]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'subscriptions')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['subscriptions:read', 'subscription:write'])]
    private $follow;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['subscriptions:read'])]
    private $user;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['subscriptions:read'])]
    private $createdAt;

    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Groups(['subscriptions:read'])]
    private $updatedAt;

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

    public function getFollow(): ?User
    {
        return $this->follow;
    }

    public function setFollow(?User $follow): self
    {
        $this->follow = $follow;

        return $this;
    }
}
