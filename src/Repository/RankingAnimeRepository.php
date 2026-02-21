<?php

namespace App\Repository;

use App\Entity\RankingAnime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RankingAnime>
 */
class RankingAnimeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RankingAnime::class);
    }

}
