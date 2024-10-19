<?php

    namespace App\Entity;

    use Doctrine\ORM\Mapping as ORM;

    #[ORM\Entity]
    #[ORM\Table(name: 'movies')]
    class Movie
    {
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column(type: 'integer')]
        private $id;

        #[ORM\Column(type: 'string', length: 255)]
        private $title;

        #[ORM\Column(type: 'string', length: 500)]
        private $description;

        #[ORM\Column(type: 'string', length: 255)]
        private $filePath; // Lien vers le fichier vidéo

        #[ORM\Column(type: 'datetime')]
        private $releaseDate;

        #[ORM\Column(type: 'string', length: 255)]
        private $genre;

        #[ORM\Column(type: 'integer')]
        private $duration; // Durée du film en minutes

        public function getId(): ?int
        {
            return $id;
        }

        public function getTitle(): ?string
        {
            return $title;
        }

        public function setTitle(string $title): self
        {
            $this->title = $title;
            return $this;
        }

        public function getDescription(): ?string
        {
            return $description;
        }

        public function setDescription(string $description): self
        {
            $this->description = $description;
            return $this;
        }

        public function getFilePath(): ?string
        {
            return $filePath;
        }

        public function setFilePath(string $filePath): self
        {
            $this->filePath = $filePath;
            return $this;
        }

        public function getReleaseDate(): ?\DateTimeInterface
        {
            return $releaseDate;
        }

        public function setReleaseDate(\DateTimeInterface $releaseDate): self
        {
            $this->releaseDate = $releaseDate;
            return $this;
        }

        public function getGenre(): ?string
        {
            return $genre;
        }

        public function setGenre(string $genre): self
        {
            $this->genre = $genre;
            return $this;
        }

        public function getDuration(): ?int
        {
            return $duration;
        }

        public function setDuration(int $duration): self
        {
            $this->duration = $duration;
            return $this;
        }
    }
