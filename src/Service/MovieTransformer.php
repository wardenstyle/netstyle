<?php

// transformer l'entité en tableau associatif. Vous pouvez m'appeler dans le constructeur du controller si besoin
namespace App\Service;

class MovieTransformer
{
    public function transform(Movie $movie): array
    {
        return [
            'id' => $movie->getId(),
            'title' => $movie->getTitle(),
            'description' => $movie->getDescription(),
            'genre' => $movie->getGenre(),
            'duration' => $movie->getDuration(),
        ];
    }

    public function transformCollection(array $movies): array
    {
        return array_map([$this, 'transform'], $movies);
    }
}