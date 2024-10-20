<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use App\Service\MovieTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MovieController extends AbstractController
{
    private $movieTransformer;

    public function __construct(MovieTransformer $movieTransformer)
    {
        $this->movieTransformer = $movieTransformer;
    }

    /**
     * @Route("/movies", name="movie_list", methods={"GET"})
     */
    public function getMovies(EntityManagerInterface $entityManager): JsonResponse
    {
        $movies = $entityManager->getRepository(Movie::class)->findAll();
        // Transformer les entités Movie en tableau associatif
        $data = [];
        foreach ($movies as $movie) {
            $data[] = [
                'id' => $movie->getId(),
                'title' => $movie->getTitle(),
                'description' => $movie->getDescription(),
                'genre' => $movie->getGenre(),
                'duration' => $movie->getDuration(),
            ];
        }
        // ou on peut faire comme ça:  $data = array_map(fn(Movie $movie) => $movie->toArray(), $movies);

        return new JsonResponse($data);
    }

    /**
     * @Route("/movies/{id}", name="movie_show", methods={"GET"})
     */
    public function getMovie(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $movie = $entityManager->getRepository(Movie::class)->find($id);
        
        if (!$movie) {
            return $this->json(['error' => 'Movie not found'], 404);
        }

        return new JsonResponse($movie->toArray());
    }

    /**
     * @Route("/movies", name="movie_create", methods={"POST"})
     */
    public function create(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        // Récupérer les données JSON de la requête
        $data = json_decode($request->getContent(), true);

        // Créer une nouvelle instance de Movie
        $movie = new Movie();
        $movie->setTitle($data['title']);
        $movie->setDescription($data['description'] ?? null);
        $movie->setGenre($data['genre']);
        $movie->setDuration($data['duration']);

        // Validation
        $errors = $validator->validate($movie);
        if (count($errors) > 0) {
            return new JsonResponse((string) $errors, JsonResponse::HTTP_BAD_REQUEST);
        }

        // Enregistrer l'entité Movie dans la base de données
        $entityManager->persist($movie);
        $entityManager->flush();

        return new JsonResponse(['id' => $movie->getId()], JsonResponse::HTTP_CREATED);
    }

    /**
     * @Route("/search", name="movies_search", methods={"GET"})
     */
    public function searchByTitle(Request $request, MovieRepository $movieRepository): JsonResponse
    {
        // Récupérer le paramètre de titre depuis l'URL
        $title = $request->query->get('title');

        // Si le paramètre est manquant
        if (!$title) {
            return new JsonResponse(['message' => 'Title parameter is missing'], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Appeler la méthode de recherche dans le repository
        $movies = $movieRepository->findByTitle($title);

        // Vérifier si des films ont été trouvés
        if (!$movies) {
            return new JsonResponse(['message' => 'No movies found'], JsonResponse::HTTP_NOT_FOUND);
        }
        // Transformer les entités Movie en tableau associatif
        $data = array_map(fn(Movie $movie) => $movie->toArray(), $movies);
        // ou en utilisant le service : $data = $this->movieTransformer->transformCollection($movies);

        return new JsonResponse($data);
    }
}
