<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\KalenderRepository")
 */
class Kalender
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $kalender;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\KalenderTekst", mappedBy="kalender")
     */
    private $y;

    public function __construct()
    {
        $this->y = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getKalender(): ?string
    {
        return $this->kalender;
    }

    public function setKalender(string $kalender): self
    {
        $this->kalender = $kalender;

        return $this;
    }

    /**
     * @return Collection|KalenderTekst[]
     */
    public function getY(): Collection
    {
        return $this->y;
    }

    public function addY(KalenderTekst $y): self
    {
        if (!$this->y->contains($y)) {
            $this->y[] = $y;
            $y->setKalender($this);
        }

        return $this;
    }

    public function removeY(KalenderTekst $y): self
    {
        if ($this->y->contains($y)) {
            $this->y->removeElement($y);
            // set the owning side to null (unless already changed)
            if ($y->getKalender() === $this) {
                $y->setKalender(null);
            }
        }

        return $this;
    }
}
