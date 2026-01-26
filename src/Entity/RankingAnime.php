<?php

namespace App\Entity;

use App\Repository\RankingAnimeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RankingAnimeRepository::class)]
class RankingAnime
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'rankingAnimes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?anime $anime = null;

    #[ORM\ManyToOne(inversedBy: 'rankingAnimes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ranking $ranking = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAnime(): ?anime
    {
        return $this->anime;
    }

    public function setAnime(?anime $anime): static
    {
        $this->anime = $anime;

        return $this;
    }

    public function getRanking(): ?ranking
    {
        return $this->ranking;
    }

    public function setRanking(?ranking $ranking): static
    {
        $this->ranking = $ranking;

        return $this;
    }
}
