<?php 

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
//use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;

class UserController extends AbstractController
{
    public function createUser(Request $request, EntityManagerInterface $em): Response //UserPasswordHasherInterface $passwordHasher
    {
        // Créer un nouvel utilisateur
        $user = new User();
        $user->setEmail('user@example.com');
        
        $plaintextPassword = 'bonjour';
        $hashedPassword = md5($plaintextPassword);
        $user->setPassword($hashedPassword);
        
        // Définir les rôles
        $user->setRoles(['ROLE_USER']);
        
        $em->persist($user);
        $em->flush();

        return new Response('Utilisateur créé avec succès');
    }
}
