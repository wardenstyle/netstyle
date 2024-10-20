<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Entity\Movie;
use App\Repository\MovieRepository;
use App\Entity\UserMovieProgress;
use App\Repository\UserMovieProgressRepository;
use App\Service\MovieProgressTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserMovieProgressController extends AbstractController
{
    private $movieProgressTransformer;

    public function __construct(MovieProgressTransformer $movieProgressTransformer)
    {
        $this->movieProgressTransformer = $movieProgressTransformer;
    }

    /**
     * sauvegarder la progression de l'utilisateur
     * @Route("/user/{userId}/movie/{movieId}/progress/save", name="save_movie_progress", methods={"POST"})
     */
    public function saveMovieProgress(
        int $userId,
        int $movieId,
        Request $request,
        EntityManagerInterface $em,
        UserMovieProgressRepository $progressRepository,
        MovieProgressTransformer $movieProgressTransformer // Injection du service
    ): JsonResponse {
        // Récupérer la progression depuis les paramètres GET (URL) au lieu du corps de la requête
        $newProgress = $request->query->get('progress', 0); // Valeur par défaut : 0

        // Trouver la progression de l'utilisateur pour ce film
        $progress = $progressRepository->findOneBy(['user' => $userId, 'movie' => $movieId]);

        // Si aucune progression n'existe, on crée un nouvel enregistrement
        if (!$progress) {
            $progress = new UserMovieProgress();
            $progress->setUser($em->getRepository(User::class)->find($userId)); // Récupère l'utilisateur
            $progress->setMovie($em->getRepository(Movie::class)->find($movieId)); // Récupère le film
        }

        // Mettre à jour la progression
        $progress->setProgress($newProgress);

        // Sauvegarder la progression
        $em->persist($progress);
        $em->flush();

        // Transformer en tableau associatif avec le service
        $data = $movieProgressTransformer->transform($progress);

        return new JsonResponse($data);
    }


    /**
     * Récupérer la progression de l'utilisateur
     * @Route("/user/{userId}/movie/{movieId}/progress", name="user_movie_progress", methods={"GET"})
     */
    public function getUserMovieProgress(
        int $userId,
        int $movieId,
        UserMovieProgressRepository $progressRepository,
        MovieProgressTransformer $movieProgressTransformer // Injection du service
    ): JsonResponse {
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
    
        // Transformer en tableau associatif avec le service
        $data = $movieProgressTransformer->transform($progress);
    
        return new JsonResponse($data);
    }

}
