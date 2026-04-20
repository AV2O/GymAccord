<?php

namespace App\Entity;

use App\Repository\SubscribesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SubscribesRepository::class)]
class Subscribes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $amount = null;

    #[ORM\Column(length: 100)]
    private ?string $name_subscribe = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * @var Collection<int, Take>
     */
    #[ORM\OneToMany(targetEntity: Take::class, mappedBy: 'subscribe')]
    private Collection $takes;

    /**
     * @var Collection<int, WorkshopsType>
     */
    #[ORM\ManyToMany(targetEntity: WorkshopsType::class)]
    private Collection $workshops_type;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\OneToMany(targetEntity: Users::class, mappedBy: 'subscribe', orphanRemoval: true)]    
    private Collection $users;

    public function __construct()
    {
        $this->takes = new ArrayCollection();
        $this->workshops_type = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getNameSubscribe(): ?string
    {
        return $this->name_subscribe;
    }

    public function setNameSubscribe(string $name_subscribe): static
    {
        $this->name_subscribe = $name_subscribe;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection<int, Take>
     */
    public function getTakes(): Collection
    {
        return $this->takes;
    }

    public function addTake(Take $take): static
    {
        if (!$this->takes->contains($take)) {
            $this->takes->add($take);
            $take->setSubscribe($this);
        }

        return $this;
    }

    public function removeTake(Take $take): static
    {
        if ($this->takes->removeElement($take)) {
            // set the owning side to null (unless already changed)
            if ($take->getSubscribe() === $this) {
                $take->setSubscribe(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, WorkshopsType>
     */
    public function getWorkshopsType(): Collection
    {
        return $this->workshops_type;
    }

    public function addWorkshopsType(WorkshopsType $workshopsType): static
    {
        if (!$this->workshops_type->contains($workshopsType)) {
            $this->workshops_type->add($workshopsType);
        }

        return $this;
    }

    public function removeWorkshopsType(WorkshopsType $workshopsType): static
    {
        $this->workshops_type->removeElement($workshopsType);

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setSubscribe($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getSubscribe() === $this) {
                $user->setSubscribe(null);
            }
        }

        return $this;
    }
}
