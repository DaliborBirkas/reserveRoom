<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'reservations')]
    private $user;

    #[ORM\ManyToOne(targetEntity: Room::class, inversedBy: 'reservations')]
    private $room;

    #[ORM\Column(type: 'string', length: 255)]
    private $timeFrom;

    #[ORM\Column(type: 'string', length: 255)]
    private $timeTo;

    #[ORM\Column(type: 'date')]
    private $date;

    #[ORM\Column(type: 'array')]
    private $Users = [];

    #[ORM\Column(type: 'array', nullable: true)]
    private $accepted = [];





    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getRoom(): ?Room
    {
        return $this->room;
    }

    public function setRoom(?Room $room): self
    {
        $this->room = $room;

        return $this;
    }

    public function getTimeFrom(): ?string
    {
        return $this->timeFrom;
    }

    public function setTimeFrom(string $timeFrom): self
    {
        $this->timeFrom = $timeFrom;

        return $this;
    }

    public function getTimeTo(): ?string
    {
        return $this->timeTo;
    }

    public function setTimeTo(string $timeTo): self
    {
        $this->timeTo = $timeTo;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getUsers(): ?array
    {
        return $this->Users;
    }

    public function setUsers(array $Users): self
    {
        $this->Users = $Users;

        return $this;
    }

    public function getAccepted(): ?array
    {
        return $this->accepted;
    }

    public function setAccepted(?array $accepted): self
    {
        $this->accepted = $accepted;

        return $this;
    }





}
