<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
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

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $image = null;

    /**
     * @var Collection<int, Ranking>
     */
    #[ORM\OneToMany(targetEntity: Ranking::class, mappedBy: 'category')]
    private Collection $rankings;

    /**
     * @var Collection<int, Anime>
     */
    #[ORM\ManyToMany(targetEntity: Anime::class, inversedBy: 'category')]
    #[ORM\JoinTable(name: 'category_anime')]
    #[ORM\JoinColumn(name: 'id_category', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'id_anime', referencedColumnName: 'id')]
    private Collection $AnimeCategory;

    public function __construct()
    {
        $this->rankings = new ArrayCollection();
        $this->AnimeCategory = new ArrayCollection();
    }

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
     * @return Collection<int, Anime>
     */
    public function getAnimeCategory(): Collection
    {
        return $this->AnimeCategory;
    }

    public function addAnimeCategory(Anime $animeCategory): static
    {
        if (!$this->AnimeCategory->contains($animeCategory)) {
            $this->AnimeCategory->add($animeCategory);
        }

        return $this;
    }

    public function removeAnimeCategory(Anime $animeCategory): static
    {
        $this->AnimeCategory->removeElement($animeCategory);

        return $this;
    }
}
