<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Component\User\Dtos\RefreshTokenRequestDto;
use App\Controller\DeleteAction;
use App\Controller\UserAboutMeAction;
use App\Controller\UserAuthAction;
use App\Controller\UserAuthByRefreshTokenAction;
use App\Controller\UserChangePasswordAction;
use App\Controller\UserCreateAction;
use App\Controller\UserIsUniqueEmailAction;
use App\Entity\Interfaces\CreatedAtSettableInterface;
use App\Entity\Interfaces\IsDeletedSettableInterface;
use App\Entity\Interfaces\UpdatedAtSettableInterface;
use App\Repository\UserRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    collectionOperations: [
        'get'                => [
            'security'              => "is_granted('ROLE_ADMIN')",
            'normalization_context' => ['groups' => ['users:read']],
        ],
        'post'               => [
            'controller' => UserCreateAction::class,
        ],
        'aboutMe'            => [
            'controller'      => UserAboutMeAction::class,
            'method'          => 'get',
            'path'            => 'users/about_me',
            'openapi_context' => [
                'summary'    => 'Shows info about the authenticated user',
//                'parameters' => [
//                    [
//                        'in'       => 'query',
//                        'name'     => 'test',
//                        'type'     => 'string',
//                        'required' => true,
//                        'example'  => '{"id": 1, "hash": "e9151ae9de2d5a3cd1d16834431a0317"}',
//                    ],
//                ],
            ],
        ],
        'auth'               => [
            'controller'      => UserAuthAction::class,
            'method'          => 'post',
            'path'            => 'users/auth',
            'openapi_context' => ['summary' => 'Authorization'],
        ],
        'authByRefreshToken' => [
            'controller'      => UserAuthByRefreshTokenAction::class,
            'method'          => 'post',
            'path'            => 'users/auth/refreshToken',
            'openapi_context' => ['summary' => 'Authorization by refreshToken'],
            'input'           => RefreshTokenRequestDto::class,
        ],
        'isUniqueEmail'      => [
            'controller'              => UserIsUniqueEmailAction::class,
            'method'                  => 'post',
            'path'                    => 'users/is_unique_email',
            'openapi_context'         => ['summary' => 'Checks email for uniqueness'],
            'denormalization_context' => ['groups' => ['user:isUniqueEmail:write']],
        ],
    ],
    itemOperations: [
        'get'            => [
            'security' => "object == user || is_granted('ROLE_ADMIN')",
        ],
        'put'            => [
            'security'                => "object == user || is_granted('ROLE_ADMIN')",
            'denormalization_context' => ['groups' => ['user:put:write']],
        ],
        'delete'         => [
            'controller' => DeleteAction::class,
            'security'   => "object == user || is_granted('ROLE_ADMIN')",
        ],
        'changePassword' => [
            'controller'              => UserChangePasswordAction::class,
            'method'                  => 'put',
            'path'                    => 'users/{id}/password',
            'security'                => "object == user || is_granted('ROLE_ADMIN')",
            'openapi_context'         => ['summary' => 'Changes password'],
            'denormalization_context' => ['groups' => ['user:changePassword:write']],
        ],
    ],
    denormalizationContext: ['groups' => ['user:write']],
    normalizationContext: ['groups' => ['user:read', 'users:read']],
)]
#[ApiFilter(OrderFilter::class, properties: ['id', 'createdAt', 'updatedAt', 'email'])]
#[ApiFilter(SearchFilter::class, properties: ['id' => 'exact', 'email' => 'partial'])]
#[UniqueEntity('email', message: 'This email is already used')]
#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements
    UserInterface,
    CreatedAtSettableInterface,
    UpdatedAtSettableInterface,
    IsDeletedSettableInterface,
    PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['users:read'])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Email]
    #[Groups(['users:read', 'user:write', 'user:put:write', 'user:isUniqueEmail:write'])]
    private $email;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Length(min: 6)]
    #[Groups(['user:write', 'user:changePassword:write'])]
    private $password;

    #[ORM\Column(type: 'array')]
    #[Groups(['user:read'])]
    private $roles = [];

    #[ORM\Column(type: 'datetime')]
    #[Groups(['user:read'])]
    private $createdAt;

    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Groups(['user:read'])]
    private $updatedAt;

    #[ORM\Column(type: 'boolean')]
    private $isDeleted = false;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Post::class)]
    private $posts;

    #[ORM\OneToMany(mappedBy: 'withUser', targetEntity: Chat::class)]
    private $chats;

    #[ORM\OneToMany(mappedBy: 'forUser', targetEntity: Notification::class)]
    private $notifications;

    #[ORM\OneToMany(mappedBy: 'follow', targetEntity: Subscription::class)]
    private $subscriptions;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Person::class)]
    #[Groups(['comments:read', 'commentLikes:read', 'messages:read'])]
    private $persons;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->chats = new ArrayCollection();
        $this->notifications = new ArrayCollection();
        $this->subscriptions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function addRole(string $role): self
    {
        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    public function deleteRole(string $role): self
    {
        $roles = $this->roles;

        foreach ($roles as $roleKey => $roleName) {
            if ($roleName === $role) {
                unset($roles[$roleKey]);
                $this->setRoles($roles);
            }
        }

        return $this;
    }

    public function getSalt(): string
    {
        return '';
    }

    public function getUsername(): string
    {
        return $this->getEmail();
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
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

    public function getUserIdentifier(): string
    {
        return (string)$this->getId();
    }

    /**
     * @return Collection<int, Post>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setUser($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getUser() === $this) {
                $post->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Chat>
     */
    public function getChats(): Collection
    {
        return $this->chats;
    }

    public function addChat(Chat $chat): self
    {
        if (!$this->chats->contains($chat)) {
            $this->chats[] = $chat;
            $chat->setWithUser($this);
        }

        return $this;
    }

    public function removeChat(Chat $chat): self
    {
        if ($this->chats->removeElement($chat)) {
            // set the owning side to null (unless already changed)
            if ($chat->getWithUser() === $this) {
                $chat->setWithUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Notification>
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notification $notification): self
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications[] = $notification;
            $notification->setForUser($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): self
    {
        if ($this->notifications->removeElement($notification)) {
            // set the owning side to null (unless already changed)
            if ($notification->getForUser() === $this) {
                $notification->setForUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Subscription>
     */
    public function getSubscriptions(): Collection
    {
        return $this->subscriptions;
    }

    public function addSubscription(Subscription $subscription): self
    {
        if (!$this->subscriptions->contains($subscription)) {
            $this->subscriptions[] = $subscription;
            $subscription->setFollow($this);
        }

        return $this;
    }

    public function removeSubscription(Subscription $subscription): self
    {
        if ($this->subscriptions->removeElement($subscription)) {
            // set the owning side to null (unless already changed)
            if ($subscription->getFollow() === $this) {
                $subscription->setFollow(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Person>
     */
    public function getPersons(): Collection
    {
        return $this->persons;
    }

    public function addPerson(Person $person): self
    {
        if (!$this->persons->contains($person)) {
            $this->persons[] = $person;
            $person->setUser($this);
        }

        return $this;
    }
}
