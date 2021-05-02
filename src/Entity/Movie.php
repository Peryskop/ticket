<?php

namespace App\Entity;

use App\Repository\MovieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MovieRepository::class)
 */
class Movie
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
    private $title;

    /**
     * @ORM\OneToMany(targetEntity=MovieDate::class, mappedBy="movie")
     */
    private $movieDates;

    /**
     * @ORM\ManyToOne(targetEntity=Cinema::class, inversedBy="movies")
     * @ORM\JoinColumn(nullable=false)
     */
    private $cinema;

    /**
     * @ORM\Column(type="integer")
     */
    private $status = 0;

    public function __construct()
    {
        $this->movieDates = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection|MovieDate[]
     */
    public function getMovieDates(): Collection
    {
        return $this->movieDates;
    }

    public function addMovieDate(MovieDate $movieDate): self
    {
        if (!$this->movieDates->contains($movieDate)) {
            $this->movieDates[] = $movieDate;
            $movieDate->setMovie($this);
        }

        return $this;
    }

    public function removeMovieDate(MovieDate $movieDate): self
    {
        if ($this->movieDates->removeElement($movieDate)) {
            // set the owning side to null (unless already changed)
            if ($movieDate->getMovie() === $this) {
                $movieDate->setMovie(null);
            }
        }

        return $this;
    }

    public function getCinema(): ?Cinema
    {
        return $this->cinema;
    }

    public function setCinema(?Cinema $cinema): self
    {
        $this->cinema = $cinema;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }
}
