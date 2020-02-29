<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ToernooienRepository")
 */
class Toernooien
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
     * @ORM\Column(type="datetime")
     */
    private $datum;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SpelersToernooien", mappedBy="toernooiId")
     */
    private $spelersToernooiens;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Wedstrijden", mappedBy="toernooiId")
     */
    private $wedstrijdens;

    public function __construct()
    {
        $this->spelersToernooiens = new ArrayCollection();
        $this->wedstrijdens = new ArrayCollection();
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

    public function getDatum(): ?\DateTimeInterface
    {
        return $this->datum;
    }

    public function setDatum(\DateTimeInterface $datum): self
    {
        $this->datum = $datum;

        return $this;
    }

    /**
     * @return Collection|SpelersToernooien[]
     */
    public function getSpelersToernooiens(): Collection
    {
        return $this->spelersToernooiens;
    }

    public function addSpelersToernooien(SpelersToernooien $spelersToernooien): self
    {
        if (!$this->spelersToernooiens->contains($spelersToernooien)) {
            $this->spelersToernooiens[] = $spelersToernooien;
            $spelersToernooien->setToernooiId($this);
        }

        return $this;
    }

    public function removeSpelersToernooien(SpelersToernooien $spelersToernooien): self
    {
        if ($this->spelersToernooiens->contains($spelersToernooien)) {
            $this->spelersToernooiens->removeElement($spelersToernooien);
            // set the owning side to null (unless already changed)
            if ($spelersToernooien->getToernooiId() === $this) {
                $spelersToernooien->setToernooiId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Wedstrijden[]
     */
    public function getWedstrijdens(): Collection
    {
        return $this->wedstrijdens;
    }

    public function addWedstrijden(Wedstrijden $wedstrijden): self
    {
        if (!$this->wedstrijdens->contains($wedstrijden)) {
            $this->wedstrijdens[] = $wedstrijden;
            $wedstrijden->setToernooiId($this);
        }

        return $this;
    }

    public function removeWedstrijden(Wedstrijden $wedstrijden): self
    {
        if ($this->wedstrijdens->contains($wedstrijden)) {
            $this->wedstrijdens->removeElement($wedstrijden);
            // set the owning side to null (unless already changed)
            if ($wedstrijden->getToernooiId() === $this) {
                $wedstrijden->setToernooiId(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return strval($this->naam);
    }

}
