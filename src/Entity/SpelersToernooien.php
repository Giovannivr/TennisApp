<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SpelersToernooienRepository")
 */
class SpelersToernooien
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Spelers", inversedBy="spelersToernooiens")
     * @ORM\JoinColumn(nullable=false)
     */
    private $spelerId;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Toernooien", inversedBy="spelersToernooiens")
     * @ORM\JoinColumn(nullable=false)
     */
    private $toernooiId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSpelerId(): ?Spelers
    {
        return $this->spelerId;
    }

    public function setSpelerId(?Spelers $spelerId): self
    {
        $this->spelerId = $spelerId;

        return $this;
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
}
