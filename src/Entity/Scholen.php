<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ScholenRepository")
 */
class Scholen
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $naam;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Spelers", mappedBy="schoolId")
     */
    private $spelers;

    public function __construct()
    {
        $this->spelers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNaam(): ?string
    {
        return $this->naam;
    }

    public function setNaam(string $naam): self
    {
        $this->naam = $naam;

        return $this;
    }

    /**
     * @return Collection|Spelers[]
     */
    public function getSpelers(): Collection
    {
        return $this->spelers;
    }

    public function addSpeler(Spelers $speler): self
    {
        if (!$this->spelers->contains($speler)) {
            $this->spelers[] = $speler;
            $speler->setSchoolId($this);
        }

        return $this;
    }

    public function removeSpeler(Spelers $speler): self
    {
        if ($this->spelers->contains($speler)) {
            $this->spelers->removeElement($speler);
            // set the owning side to null (unless already changed)
            if ($speler->getSchoolId() === $this) {
                $speler->setSchoolId(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return strval($this->naam);
    }

}
