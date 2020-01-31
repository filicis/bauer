<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LocaleRepository")
 */
class Locale
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $locale;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\KalenderTekst", mappedBy="locale")
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

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function setLocale(string $locale): self
    {
        $this->locale = $locale;

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
            $kalenderTekst->setLocale($this);
        }

        return $this;
    }

    public function removeKalenderTekst(KalenderTekst $kalenderTekst): self
    {
        if ($this->kalenderTeksts->contains($kalenderTekst)) {
            $this->kalenderTeksts->removeElement($kalenderTekst);
            // set the owning side to null (unless already changed)
            if ($kalenderTekst->getLocale() === $this) {
                $kalenderTekst->setLocale(null);
            }
        }

        return $this;
    }
}
