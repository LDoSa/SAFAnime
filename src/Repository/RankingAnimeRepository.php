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

    public function getRankingByCategory(int $categoryId){
        return $this->createQueryBuilder('ra')
            ->join('ra.anime', 'a')
            ->join('ra.ranking', 'r')
            ->join('r.category', 'c')
            ->addSelect('a')
            ->addSelect('AVG(ra.position) as avgPosition')
            ->andWhere('c.id = :categoryId')
            ->setParameter('categoryId', $categoryId)
            ->groupBy('ra.anime')
            ->orderBy('avgPosition', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
