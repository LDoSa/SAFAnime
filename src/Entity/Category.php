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

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $image = null;

    #[ORM\ManyToMany(targetEntity: Anime::class, inversedBy: 'categories')]
    #[ORM\JoinTable(name: 'category_anime')]
    private Collection $animes;

    #[ORM\OneToMany(targetEntity: Ranking::class, mappedBy: 'category', orphanRemoval: true)]
    private Collection $rankings;

    public function __construct()
    {
        $this->animes = new ArrayCollection();
        $this->rankings = new ArrayCollection();
    }

    // ---------------- Propiedades ----------------
    public function getId(): ?int
    {
        return $this->id;
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

    // ---------------- Animes ----------------
    public function getAnimes(): Collection
    {
        return $this->animes;
    }

    public function addAnime(Anime $anime): static
    {
        if (!$this->animes->contains($anime)) {
            $this->animes->add($anime);
            $anime->addCategory($this);
        }
        return $this;
    }

    public function removeAnime(Anime $anime): static
    {
        if ($this->animes->removeElement($anime)) {
            $anime->removeCategory($this);
        }
        return $this;
    }

    // ---------------- Rankings ----------------
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
            if ($ranking->getCategory() === $this) {
                $ranking->setCategory(null);
            }
        }
        return $this;
    }
}

