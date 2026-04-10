<?php

namespace App\Entity;

use App\Repository\TakeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TakeRepository::class)]
class Take
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $is_active = null;

    #[ORM\ManyToOne(inversedBy: 'takes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Users $user = null;

    #[ORM\ManyToOne(inversedBy: 'takes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Subscribes $subscribe = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isActive(): ?bool
    {
        return $this->is_active;
    }

    public function setIsActive(bool $is_active): static
    {
        $this->is_active = $is_active;

        return $this;
    }

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getSubscribe(): ?Subscribes
    {
        return $this->subscribe;
    }

    public function setSubscribe(?Subscribes $subscribe): static
    {
        $this->subscribe = $subscribe;

        return $this;
    }
}
