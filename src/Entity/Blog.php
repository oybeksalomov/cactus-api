<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\BlogRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BlogRepository::class)]
#[ApiResource]
class Blog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: MediaObject::class)]
    private $picture;

    #[ORM\Column(type: 'integer')]
    private $followersCount;

    #[ORM\Column(type: 'integer')]
    private $followingCount;

    #[ORM\Column(type: 'string', length: 255)]
    private $nickname;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPicture(): ?MediaObject
    {
        return $this->picture;
    }

    public function setPicture(?MediaObject $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getFollowersCount(): ?int
    {
        return $this->followersCount;
    }

    public function setFollowersCount(int $followersCount): self
    {
        $this->followersCount = $followersCount;

        return $this;
    }

    public function getFollowingCount(): ?int
    {
        return $this->followingCount;
    }

    public function setFollowingCount(int $followingCount): self
    {
        $this->followingCount = $followingCount;

        return $this;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname): self
    {
        $this->nickname = $nickname;

        return $this;
    }
}
