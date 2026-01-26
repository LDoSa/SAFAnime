<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $username = null;

    #[ORM\Column(length: 100)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column]
    private ?int $rol = null;

    /**
     * @var Collection<int, Opinion>
     */
    #[ORM\OneToMany(targetEntity: Opinion::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $OpinionesUsuario;

    /**
     * @var Collection<int, Ranking>
     */
    #[ORM\OneToMany(targetEntity: Ranking::class, mappedBy: 'user')]
    private Collection $rankings;

    public function __construct()
    {
        $this->OpinionesUsuario = new ArrayCollection();
        $this->rankings = new ArrayCollection();
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

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    //Voy a modificar el getRol para que sepa cuando es ADMIN y cuando USER
    public function getRol(): array
    {
        if ($this->rol ===1){
            return ['ROLE_ADMIN'];
        }

        return ['ROLE_USER'];
    }

    public function setRol(int $rol): static
    {
        $this->rol = $rol;

        return $this;
    }

    /**
     * @return Collection<int, Opinion>
     */
    public function getOpinionesUsuario(): Collection
    {
        return $this->OpinionesUsuario;
    }

    public function addOpinionesUsuario(Opinion $opinionesUsuario): static
    {
        if (!$this->OpinionesUsuario->contains($opinionesUsuario)) {
            $this->OpinionesUsuario->add($opinionesUsuario);
            $opinionesUsuario->setUser($this);
        }

        return $this;
    }

    public function removeOpinionesUsuario(Opinion $opinionesUsuario): static
    {
        if ($this->OpinionesUsuario->removeElement($opinionesUsuario)) {
            // set the owning side to null (unless already changed)
            if ($opinionesUsuario->getUser() === $this) {
                $opinionesUsuario->setUser(null);
            }
        }

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
            $ranking->setUser($this);
        }

        return $this;
    }

    public function removeRanking(Ranking $ranking): static
    {
        if ($this->rankings->removeElement($ranking)) {
            // set the owning side to null (unless already changed)
            if ($ranking->getUser() === $this) {
                $ranking->setUser(null);
            }
        }

        return $this;
    }
}
