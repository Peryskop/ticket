<?php

namespace App\Entity;

use App\Repository\RoomRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RoomRepository::class)
 */
class Room
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=MovieDate::class, mappedBy="room")
     */
    private $movieDate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $roomNumber;

    /**
     * @ORM\OneToMany(targetEntity=Slot::class, mappedBy="room")
     */
    private $slots;

    public function __construct()
    {
        $this->movieDate = new ArrayCollection();
        $this->slots = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|MovieDate[]
     */
    public function getMovieDate(): Collection
    {
        return $this->movieDate;
    }

    public function addMovieDate(MovieDate $movieDate): self
    {
        if (!$this->movieDate->contains($movieDate)) {
            $this->movieDate[] = $movieDate;
            $movieDate->setRoom($this);
        }

        return $this;
    }

    public function removeMovieDate(MovieDate $movieDate): self
    {
        if ($this->movieDate->removeElement($movieDate)) {
            // set the owning side to null (unless already changed)
            if ($movieDate->getRoom() === $this) {
                $movieDate->setRoom(null);
            }
        }

        return $this;
    }

    public function getRoomNumber(): ?string
    {
        return $this->roomNumber;
    }

    public function setRoomNumber(string $roomNumber): self
    {
        $this->roomNumber = $roomNumber;

        return $this;
    }

    /**
     * @return Collection|Slot[]
     */
    public function getSlots(): Collection
    {
        return $this->slots;
    }

    public function addSlot(Slot $slot): self
    {
        if (!$this->slots->contains($slot)) {
            $this->slots[] = $slot;
            $slot->setRoom($this);
        }

        return $this;
    }

    public function removeSlot(Slot $slot): self
    {
        if ($this->slots->removeElement($slot)) {
            // set the owning side to null (unless already changed)
            if ($slot->getRoom() === $this) {
                $slot->setRoom(null);
            }
        }

        return $this;
    }
}
