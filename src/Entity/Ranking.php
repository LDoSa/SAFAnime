<?php

namespace App\Entity;

use App\Repository\RankingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RankingRepository::class)]
class Ranking
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'rankings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?user $user = null;

    #[ORM\ManyToOne(inversedBy: 'rankings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?category $category = null;

    /**
     * @var Collection<int, RankingAnime>
     */
    #[ORM\OneToMany(targetEntity: RankingAnime::class, mappedBy: 'ranking')]
    private Collection $rankingAnimes;

    public function __construct()
    {
        $this->rankingAnimes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): static
    {
        $this->user = $user;

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

    /**
     * @return Collection<int, RankingAnime>
     */
    public function getRankingAnimes(): Collection
    {
        return $this->rankingAnimes;
    }

    public function addRankingAnime(RankingAnime $rankingAnime): static
    {
        if (!$this->rankingAnimes->contains($rankingAnime)) {
            $this->rankingAnimes->add($rankingAnime);
            $rankingAnime->setRanking($this);
        }

        return $this;
    }

    public function removeRankingAnime(RankingAnime $rankingAnime): static
    {
        if ($this->rankingAnimes->removeElement($rankingAnime)) {
            // set the owning side to null (unless already changed)
            if ($rankingAnime->getRanking() === $this) {
                $rankingAnime->setRanking(null);
            }
        }

        return $this;
    }
}
