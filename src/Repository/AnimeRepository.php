<?php

namespace App\Repository;

use App\Entity\Anime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
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

    public function searchAnimes(string $search): QueryBuilder
    {
        $qb = $this->createQueryBuilder('a');

        if ($search) {
            $qb->andWhere('a.titulo LIKE :search')
                ->setParameter('search', '%' . $search . '%');
        }

        return $qb;
    }

    public function orderByRating($qb){
        return $qb
            ->leftJoin('a.OpinionesAnime', 'o')
            ->groupBy('a.id')
            ->orderBy('AVG(o.puntuacion)', 'DESC');
    }

    public function orderByName($qb)
    {
        return $qb->orderBy('a.titulo', 'ASC');
    }
}
