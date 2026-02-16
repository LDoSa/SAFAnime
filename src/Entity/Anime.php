<?php

namespace App\Entity;

use App\Repository\AnimeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnimeRepository::class)]
class Anime
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $ani_id = null;

    #[ORM\Column(length: 255)]
    private ?string $titulo = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $imagen = null;

    #[ORM\Column(nullable: true)]
    private ?int $episodios = null;

    /**
     * @var Collection<int, Opinion>
     */
    #[ORM\OneToMany(targetEntity: Opinion::class, mappedBy: 'anime', orphanRemoval: true)]
    private Collection $OpinionesAnime;

    /**
     * @var Collection<int, Category>
     */
    #[ORM\ManyToMany(targetEntity: Category::class, mappedBy: 'animes')]
    private Collection $categories;


    public function __construct()
    {
        $this->OpinionesAnime = new ArrayCollection();
        $this->categories = new ArrayCollection();
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

    public function getAniId(): ?int
    {
        return $this->ani_id;
    }

    public function setAniId(int $ani_id): static
    {
        $this->ani_id = $ani_id;

        return $this;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): static
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getImagen(): ?string
    {
        return $this->imagen;
    }

    public function setImagen(?string $imagen): static
    {
        $this->imagen = $imagen;

        return $this;
    }

    public function getEpisodios(): ?int
    {
        return $this->episodios;
    }

    public function setEpisodios(?int $episodios): static
    {
        $this->episodios = $episodios;

        return $this;
    }

    /**
     * @return Collection<int, Opinion>
     */
    public function getOpinionesAnime(): Collection
    {
        return $this->OpinionesAnime;
    }

    public function addOpinionesAnime(Opinion $opinionesAnime): static
    {
        if (!$this->OpinionesAnime->contains($opinionesAnime)) {
            $this->OpinionesAnime->add($opinionesAnime);
            $opinionesAnime->setAnime($this);
        }

        return $this;
    }

    public function removeOpinionesAnime(Opinion $opinionesAnime): static
    {
        if ($this->OpinionesAnime->removeElement($opinionesAnime)) {
            // set the owning side to null (unless already changed)
            if ($opinionesAnime->getAnime() === $this) {
                $opinionesAnime->setAnime(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
            $category->addAnime($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): static
    {
        if ($this->categories->removeElement($category)) {
            $category->removeAnime($this);
        }

        return $this;
    }


}
