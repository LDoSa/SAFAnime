<?php

namespace App\Repository;

use App\Entity\Anime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Anime>
 */
class AnimeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Anime::class);
    }

    public function getTopRatedAnimes(): array{
        return $this->createQueryBuilder('a')
            ->leftJoin('a.OpinionesAnime', 'o')
            ->addSelect('a AS anime', 'AVG(o.puntuacion) AS media', 'COUNT(o.id) AS total')
            ->groupBy('a.id')
            ->orderBy('media', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    public function getMostRatedAnimes(): array
    {
        return $this->createQueryBuilder('a')
            ->innerJoin('a.OpinionesAnime', 'o')
            ->addSelect('a AS anime', 'AVG(o.puntuacion) AS media', 'COUNT(o.id) AS total')
            ->groupBy('a.id')
            ->orderBy('total', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

}
