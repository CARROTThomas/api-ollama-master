<?php

namespace App\Entity;

use App\Repository\MotRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MotRepository::class)]
class Mot
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $mot = null;

    #[ORM\OneToMany(targetEntity: Vecteur::class, mappedBy: 'mot')]
    private Collection $vecteurs;

    #[ORM\OneToMany(targetEntity: MotDuJour::class, mappedBy: 'mot')]
    private Collection $motDuJours;

    public function __construct()
    {
        $this->vecteurs = new ArrayCollection();
        $this->motDuJours = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMot(): ?string
    {
        return $this->mot;
    }

    public function setMot(string $mot): static
    {
        $this->mot = $mot;

        return $this;
    }

    /**
     * @return Collection<int, Vecteur>
     */
    public function getVecteurs(): Collection
    {
        return $this->vecteurs;
    }

    public function addVecteur(Vecteur $vecteur): static
    {
        if (!$this->vecteurs->contains($vecteur)) {
            $this->vecteurs->add($vecteur);
            $vecteur->setMot($this);
        }

        return $this;
    }

    public function removeVecteur(Vecteur $vecteur): static
    {
        if ($this->vecteurs->removeElement($vecteur)) {
            // set the owning side to null (unless already changed)
            if ($vecteur->getMot() === $this) {
                $vecteur->setMot(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, MotDuJour>
     */
    public function getMotDuJours(): Collection
    {
        return $this->motDuJours;
    }

    public function addMotDuJour(MotDuJour $motDuJour): static
    {
        if (!$this->motDuJours->contains($motDuJour)) {
            $this->motDuJours->add($motDuJour);
            $motDuJour->setMot($this);
        }

        return $this;
    }

    public function removeMotDuJour(MotDuJour $motDuJour): static
    {
        if ($this->motDuJours->removeElement($motDuJour)) {
            // set the owning side to null (unless already changed)
            if ($motDuJour->getMot() === $this) {
                $motDuJour->setMot(null);
            }
        }

        return $this;
    }
}
