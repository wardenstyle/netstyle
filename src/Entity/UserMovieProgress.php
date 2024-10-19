<?php 

// src/Entity/UserMovieProgress.php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'user_movie_progress')]
class UserMovieProgress
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private $user;

    #[ORM\ManyToOne(targetEntity: Movie::class)]
    private $movie;

    #[ORM\Column(type: 'integer')]
    private $currentPosition; // En secondes

    #[ORM\Column(type: 'datetime')]
    private $updatedAt;

    public function getId(): ?int
    {
        return $id;
    }

    public function getUser(): ?User
    {
        return $user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getMovie(): ?Movie
    {
        return $movie;
    }

    public function setMovie(Movie $movie): self
    {
        $this->movie = $movie;
        return $this;
    }

    public function getCurrentPosition(): ?int
    {
        return $currentPosition;
    }

    public function setCurrentPosition(int $currentPosition): self
    {
        $this->currentPosition = $currentPosition;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
}
