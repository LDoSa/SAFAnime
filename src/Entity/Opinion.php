<?php

namespace App\Entity;

use App\Repository\OpinionRepository;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OpinionRepository::class)]
class Opinion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'OpinionesUsuario')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'OpinionesAnime')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Anime $anime = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $texto = null;

    #[Assert\Range(min: 1, max: 5, notInRangeMessage: 'La puntuación debe estar entre 1 y 5.')]
    #[ORM\Column]
    private ?int $puntuacion = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getAnime(): ?Anime
    {
        return $this->anime;
    }

    public function setAnime(?Anime $anime): static
    {
        $this->anime = $anime;

        return $this;
    }

    public function getTexto(): ?string
    {
        return $this->texto;
    }

    public function setTexto(string $texto): static
    {
        $this->texto = $texto;

        return $this;
    }

    public function getPuntuacion(): ?int
    {
        return $this->puntuacion;
    }

    public function setPuntuacion(int $puntuacion): static
    {
        $this->puntuacion = $puntuacion;

        return $this;
    }
}
