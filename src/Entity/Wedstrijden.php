<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WedstrijdenRepository")
 */
class Wedstrijden
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Toernooien", inversedBy="wedstrijdens")
     */
    private $toernooiId;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Spelers", inversedBy="wedstrijdens")
     */
    private $speler1Id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Spelers", inversedBy="wedstrijdens")
     */
    private $speler2Id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Spelers", inversedBy="wedstrijdens")
     */
    private $winnaarId;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ronde;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $score1;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $score2;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getToernooiId(): ?Toernooien
    {
        return $this->toernooiId;
    }

    public function setToernooiId(?Toernooien $toernooiId): self
    {
        $this->toernooiId = $toernooiId;

        return $this;
    }

    public function getSpeler1Id(): ?Spelers
    {
        return $this->speler1Id;
    }

    public function setSpeler1Id(?Spelers $speler1Id): self
    {
        $this->speler1Id = $speler1Id;

        return $this;
    }

    public function getSpeler2Id(): ?Spelers
    {
        return $this->speler2Id;
    }

    public function setSpeler2Id(?Spelers $speler2Id): self
    {
        $this->speler2Id = $speler2Id;

        return $this;
    }

    public function getWinnaarId(): ?Spelers
    {
        return $this->winnaarId;
    }

    public function setWinnaarId(?Spelers $winnaarId): self
    {
        $this->winnaarId = $winnaarId;

        return $this;
    }

    public function getRonde(): ?int
    {
        return $this->ronde;
    }

    public function setRonde(?int $ronde): self
    {
        $this->ronde = $ronde;

        return $this;
    }

    public function getScore1(): ?int
    {
        return $this->score1;
    }

    public function setScore1(?int $score1): self
    {
        $this->score1 = $score1;

        return $this;
    }

    public function getScore2(): ?int
    {
        return $this->score2;
    }

    public function setScore2(?int $score2): self
    {
        $this->score2 = $score2;

        return $this;
    }
}
