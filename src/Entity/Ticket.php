<?php

namespace App\Entity;

use App\Repository\TicketRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=TicketRepository::class)
 * @UniqueEntity(fields={"slot", "movieDate"})
 */
class Ticket
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $phone;

    /**
     * @ORM\ManyToOne(targetEntity=MovieDate::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $movieDate;

    /**
     * @ORM\ManyToOne(targetEntity=Slot::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $slot;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="integer")
     */
    private $purchaseType = 0;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getMovieDate(): ?MovieDate
    {
        return $this->movieDate;
    }

    public function setMovieDate(?MovieDate $movieDate): self
    {
        $this->movieDate = $movieDate;

        return $this;
    }

    public function getSlot(): ?Slot
    {
        return $this->slot;
    }

    public function setSlot(Slot $slot): self
    {
        $this->slot = $slot;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPurchaseType(): ?int
    {
        return $this->purchaseType;
    }

    public function setPurchaseType(int $purchaseType): self
    {
        $this->purchaseType = $purchaseType;

        return $this;
    }
}
