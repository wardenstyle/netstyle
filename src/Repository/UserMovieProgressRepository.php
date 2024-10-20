<?php

namespace App\Repository;

use App\Entity\UserMovieProgress;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserMovieProgress|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserMovieProgress|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserMovieProgress[]    findAll()
 * @method UserMovieProgress[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserMovieProgressRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserMovieProgress::class);
    }
    
}
