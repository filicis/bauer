<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\KalenderTekstRepository")
 */
class KalenderTekst
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Locale", inversedBy="kalenderTeksts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $locale;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Kalender", inversedBy="y")
     * @ORM\JoinColumn(nullable=false)
     */
    private $kalender;

    /**
     * @ORM\Column(type="array")
     */
    private $tekst = [];

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Bid", inversedBy="kalenderTeksts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $bid;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLocale(): ?Locale
    {
        return $this->locale;
    }

    public function setLocale(?Locale $locale): self
    {
        $this->locale = $locale;

        return $this;
    }

    public function getKalender(): ?Kalender
    {
        return $this->kalender;
    }

    public function setKalender(?Kalender $kalender): self
    {
        $this->kalender = $kalender;

        return $this;
    }

    public function getTekst(): ?array
    {
        return $this->tekst;
    }

    public function setTekst(array $tekst): self
    {
        $this->tekst = $tekst;

        return $this;
    }


    public function getBid(): ?Bid
    {
        return $this->bid;
    }

    public function setBid(?Bid $bid): self
    {
        $this->bid = $bid;

        return $this;
    }
}
