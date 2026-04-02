<?php

namespace App\Entity;

use App\Repository\ReservationsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationsRepository::class)]
class Reservations
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTime $workshop_date = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWorkshopDate(): ?\DateTime
    {
        return $this->workshop_date;
    }

    public function setWorkshopDate(\DateTime $workshop_date): static
    {
        $this->workshop_date = $workshop_date;

        return $this;
    }
}
