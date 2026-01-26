<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $image = null;

    /**
     * @var Collection<int, Ranking>
     */
    #[ORM\OneToMany(targetEntity: Ranking::class, mappedBy: 'category')]
    private Collection $rankings;

    /**
     * @var Collection<int, RankingAnime>
     */
    #[ORM\OneToMany(targetEntity: RankingAnime::class, mappedBy: 'category')]
    private Collection $rankingAnimes;

    public function __construct()
    {
        $this->rankings = new ArrayCollection();
        $this->rankingAnimes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection<int, Ranking>
     */
    public function getRankings(): Collection
    {
        return $this->rankings;
    }

    public function addRanking(Ranking $ranking): static
    {
        if (!$this->rankings->contains($ranking)) {
            $this->rankings->add($ranking);
            $ranking->setCategory($this);
        }

        return $this;
    }

    public function removeRanking(Ranking $ranking): static
    {
        if ($this->rankings->removeElement($ranking)) {
            // set the owning side to null (unless already changed)
            if ($ranking->getCategory() === $this) {
                $ranking->setCategory(null);
            }
        }

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
            $rankingAnime->setCategory($this);
        }

        return $this;
    }

    public function removeRankingAnime(RankingAnime $rankingAnime): static
    {
        if ($this->rankingAnimes->removeElement($rankingAnime)) {
            // set the owning side to null (unless already changed)
            if ($rankingAnime->getCategory() === $this) {
                $rankingAnime->setCategory(null);
            }
        }

        return $this;
    }
}
