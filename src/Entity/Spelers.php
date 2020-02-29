<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SpelersRepository")
 */
class Spelers
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Scholen", inversedBy="spelers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $schoolId;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $voornaam;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $tussenvoegsel;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $achternaam;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SpelersToernooien", mappedBy="spelerId")
     */
    private $spelersToernooiens;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Wedstrijden", mappedBy="speler1Id")
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

    public function getSchoolId(): ?Scholen
    {
        return $this->schoolId;
    }

    public function setSchoolId(?Scholen $schoolId): self
    {
        $this->schoolId = $schoolId;

        return $this;
    }

    public function getVoornaam(): ?string
    {
        return $this->voornaam;
    }

    public function setVoornaam(string $voornaam): self
    {
        $this->voornaam = $voornaam;

        return $this;
    }

    public function getTussenvoegsel(): ?string
    {
        return $this->tussenvoegsel;
    }

    public function setTussenvoegsel(?string $tussenvoegsel): self
    {
        $this->tussenvoegsel = $tussenvoegsel;

        return $this;
    }

    public function getAchternaam(): ?string
    {
        return $this->achternaam;
    }

    public function setAchternaam(string $achternaam): self
    {
        $this->achternaam = $achternaam;

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
            $spelersToernooien->setSpelerId($this);
        }

        return $this;
    }

    public function removeSpelersToernooien(SpelersToernooien $spelersToernooien): self
    {
        if ($this->spelersToernooiens->contains($spelersToernooien)) {
            $this->spelersToernooiens->removeElement($spelersToernooien);
            // set the owning side to null (unless already changed)
            if ($spelersToernooien->getSpelerId() === $this) {
                $spelersToernooien->setSpelerId(null);
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
            $wedstrijden->setSpeler1Id($this);
        }

        return $this;
    }

    public function removeWedstrijden(Wedstrijden $wedstrijden): self
    {
        if ($this->wedstrijdens->contains($wedstrijden)) {
            $this->wedstrijdens->removeElement($wedstrijden);
            // set the owning side to null (unless already changed)
            if ($wedstrijden->getSpeler1Id() === $this) {
                $wedstrijden->setSpeler1Id(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return strval($this->getVoornaam().' '.(empty($this->getTussenvoegsel())?'':$this->getTussenvoegsel().' ').$this->getAchternaam());
    }

}
