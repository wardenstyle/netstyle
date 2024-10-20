<?php

// transformer l'entité en tableau associatif. Vous pouvez m'appeler dans le constructeur du controller si besoin
namespace App\Service;
use App\Entity\UserMovieProgress;

class MovieProgressTransformer
{
    
    public function transform(UserMovieProgress $movieProgress): array
    {
        return [
            'user' => [
                'id' => $movieProgress->getUser()->getId(),
                'name' => $movieProgress->getUser()->getEmail(), // Par exemple, récupérer l'email de l'utilisateur
            ],
            'movie' => [
                'id' => $movieProgress->getMovie()->getId(),
                'title' => $movieProgress->getMovie()->getTitle(), // Par exemple, récupérer le titre du film
            ],
            'progress' => $movieProgress->getProgress(), // La progression de l'utilisateur
        ];
    }
    

    public function transformCollection(array $movieProgress): array
    {
        return array_map([$this, 'transform'], $movieProgress);
    }
}