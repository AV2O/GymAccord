<?php

namespace App\Entity;

use App\Repository\WorkshopsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WorkshopsRepository::class)]
class Workshops
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name_class = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description_class = null;

    #[ORM\Column(length: 20)]
    private ?string $day_class = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTime $start_time = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTime $end_time = null;

    #[ORM\Column(length: 100)]
    private ?string $status = null;

    #[ORM\ManyToOne(inversedBy: 'workshops')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Coachs $coach = null;

    #[ORM\ManyToOne(inversedBy: 'workshops')]
    #[ORM\JoinColumn(nullable: false)]
    private ?WorkshopsType $workshop_type = null;

    /**
     * @var Collection<int, Reservation>
     */
    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'workshop', orphanRemoval: true)]
    private Collection $reservations;

    #[ORM\Column]
    private ?int $max_capacity = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameClass(): ?string
    {
        return $this->name_class;
    }

    public function setNameClass(string $name_class): static
    {
        $this->name_class = $name_class;

        return $this;
    }

    public function getDescriptionClass(): ?string
    {
        return $this->description_class;
    }

    public function setDescriptionClass(string $description_class): static
    {
        $this->description_class = $description_class;

        return $this;
    }

    public function getDayClass(): ?string
    {
        return $this->day_class;
    }

    public function setDayClass(string $day_class): static 
    {
        $this->day_class = $day_class;
        return $this;
    }

    public function getStartTime(): ?\DateTime
    {
        return $this->start_time;
    }

    public function setStartTime(\DateTime $start_time): static
    {
        $this->start_time = $start_time;

        return $this;
    }

    public function getEndTime(): ?\DateTime
    {
        return $this->end_time;
    }

    public function setEndTime(\DateTime $end_time): static
    {
        $this->end_time = $end_time;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCoach(): ?Coachs
    {
        return $this->coach;
    }

    public function setCoach(?Coachs $coach): static
    {
        $this->coach = $coach;

        return $this;
    }

    public function getWorkshopType(): ?WorkshopsType
    {
        return $this->workshop_type;
    }

    public function setWorkshopType(?WorkshopsType $workshop_type): static
    {
        $this->workshop_type = $workshop_type;

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): static
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setWorkshop($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): static
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getWorkshop() === $this) {
                $reservation->setWorkshop(null);
            }
        }

        return $this;
    }

    public function getMaxCapacity(): ?int
    {
        return $this->max_capacity;
    }

    public function setMaxCapacity(int $max_capacity): static
    {
        $this->max_capacity = $max_capacity;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }
}
