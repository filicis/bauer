<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BidRepository")
 */
class Bid
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $bid;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\KalenderTekst", mappedBy="bid")
     */
    private $kalenderTeksts;

    public function __construct()
    {
        $this->kalenderTeksts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBid(): ?int
    {
        return $this->bid;
    }

    public function setBid(int $bid): self
    {
        $this->bid = $bid;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|KalenderTekst[]
     */
    public function getKalenderTeksts(): Collection
    {
        return $this->kalenderTeksts;
    }

    public function addKalenderTekst(KalenderTekst $kalenderTekst): self
    {
        if (!$this->kalenderTeksts->contains($kalenderTekst)) {
            $this->kalenderTeksts[] = $kalenderTekst;
            $kalenderTekst->setBid($this);
        }

        return $this;
    }

    public function removeKalenderTekst(KalenderTekst $kalenderTekst): self
    {
        if ($this->kalenderTeksts->contains($kalenderTekst)) {
            $this->kalenderTeksts->removeElement($kalenderTekst);
            // set the owning side to null (unless already changed)
            if ($kalenderTekst->getBid() === $this) {
                $kalenderTekst->setBid(null);
            }
        }

        return $this;
    }
}
