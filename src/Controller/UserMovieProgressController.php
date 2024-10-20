<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use App\Entity\UserMovieProgress;
use App\Repository\UserMovieProgressRepository;
use App\Service\MovieTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserMovieProgressController extends AbstractController
{
    private $movieTransformer;

    public function __construct(MovieTransformer $movieTransformer)
    {
        //services
        $this->movieTransformer = $movieTransformer;
    }

    /**
     * sauvegarder la progression de l'utilisateur
     * @Route("/movies/{id}/progress", name="save_movie_progress", methods={"POST"})
     */
    public function saveMovieProgress(int $id, Request $request, MovieRepository $movieRepository, 
    UserMovieProgressRepository $progressRepository): Response
    {
        // Récupérer le film
        $movie = $movieRepository->find($id);
        if (!$movie) {
            return new Response("Movie not found", 404);
        }

        // Récupérer l'utilisateur (ici on suppose que l'utilisateur est authentifié, il faudra le récupérer)
        $user = $this->getUser(); // Utilisation de l'utilisateur connecté

        if (!$user) {
            return new Response("User not authenticated", 401);
        }

        // Récupérer le progrès à partir de la requête (par exemple en JSON)
        $data = json_decode($request->getContent(), true);
        $progress = $data['progress'] ?? null;

        if (!$progress || !is_int($progress)) {
            return new Response("Invalid progress value", 400);
        }

        // Vérifier si un enregistrement existe déjà pour cet utilisateur et ce film
        $userMovieProgress = $progressRepository->findOneBy(['user' => $user, 'movie' => $movie]);

        if (!$userMovieProgress) {
            // Si non, créer un nouvel enregistrement
            $userMovieProgress = new UserMovieProgress();
            $userMovieProgress->setUser($user);
            $userMovieProgress->setMovie($movie);
        }

        // Mettre à jour le progrès
        $userMovieProgress->setProgress($progress);

        // Enregistrer dans la base de données
        $em = $this->getDoctrine()->getManager();
        $em->persist($userMovieProgress);
        $em->flush();

        return new Response("Progress saved", 200);
    }

    /**
     * récupérer la progression de l'utilisateur
     * @Route("/user/{userId}/movie/{movieId}/progress", name="user_movie_progress", methods={"GET"})
     */
    public function getUserMovieProgress(int $userId, int $movieId, UserMovieProgressRepository $progressRepository): JsonResponse
    {
        // Trouver la progression de l'utilisateur pour le film donné
        $progress = $progressRepository->findOneBy(['user' => $userId, 'movie' => $movieId]);

        // Si aucune progression n'existe, on renvoie une progression de 0 (le début du film)
        if (!$progress) {
            return new JsonResponse([
                'user' => $userId,
                'movie' => $movieId,
                'progress' => 0, // Commencer au début
            ]);
        }

        // Transformer en tableau associatif
        $response = [
            'user' => $progress->getUser()->getId(),
            'movie' => $progress->getMovie()->getId(),
            'progress' => $progress->getProgress(), // La position du film
        ];

        return new JsonResponse($response);
    }

}
