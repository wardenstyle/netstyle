<?php

namespace App\Controller;

use App\Entity\Movie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    #[Route('/movies', name: 'get_movies', methods: ['GET'])]
    public function getMovies(EntityManagerInterface $entityManager): JsonResponse
    {
        $movies = $entityManager->getRepository(Movie::class)->findAll();
        return $this->json($movies);
    }

    #[Route('/movies/{id}', name: 'get_movie', methods: ['GET'])]
    public function getMovie(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $movie = $entityManager->getRepository(Movie::class)->find($id);
        
        if (!$movie) {
            return $this->json(['error' => 'Movie not found'], 404);
        }

        return $this->json($movie);
    }

    //#[Route('/movies', name: 'movies_create', methods: ['POST'])]
    //public function createMovie(Request $request, EntityManagerInterface $entityManager): JsonResponse
    public function createMovie(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['title'])) {
            return $this->json(['error' => 'Title is required'], Response::HTTP_BAD_REQUEST);
        }

        $newMovie = [
            'id' => rand(100, 999), // Génération d'un ID fictif
            'title' => $data['title'],
        ];

        // Retourne une réponse JSON avec le film créé
        return $this->json($newMovie, Response::HTTP_CREATED);

        // $movie = new Movie();
        // $movie->setTitle($data['title']);
        // $movie->setDescription($data['description']);
        // $movie->setFilePath($data['filePath']);
        // $movie->setReleaseDate(new \DateTime($data['releaseDate']));
        // $movie->setGenre($data['genre']);
        // $movie->setDuration($data['duration']);

        // $entityManager->persist($movie);
        // $entityManager->flush();

        // return $this->json($movie);
    }
}
