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

    #[ORM\Column]
    private ?int $position = null;

    #[ORM\ManyToOne(inversedBy: 'rankingAnimes')]
    private ?category $category = null;

    #[ORM\ManyToOne(inversedBy: 'rankingAnimes')]
    private ?ranking $Ranking = null;

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getCategory(): ?category
    {
        return $this->category;
    }

    public function setCategory(?category $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getRanking(): ?ranking
    {
        return $this->Ranking;
    }

    public function setRanking(?ranking $Ranking): static
    {
        $this->Ranking = $Ranking;

        return $this;
    }
}
