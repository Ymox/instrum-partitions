<?php

namespace App\Entity;

use App\Listener\InstrumentListener;
use App\Repository\InstrumentRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Table(name: 'instrument')]
#[ORM\Entity(repositoryClass: InstrumentRepository::class)]
class Instrument
{
    #[ORM\Column]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 7, nullable: true)]
    private ?string $key = null;

    #[ORM\Column(length: 15, nullable: true)]
    private ?string $family = null;

    #[ORM\Column(type: 'boolean')]
    public ?bool $common = null;

    #[ORM\OneToMany(targetEntity: Part::class, mappedBy: 'instrument', cascade: ['persist', 'remove'], orphanRemoval: true, fetch: 'EXTRA_LAZY')]
    private Collection $parts;

    // NOT A PERSISTED PROPERTY
    private string $display = '';

    public function __construct()
    {
        $this->parts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setName(string $name): ?self
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setKey(string $key): static
    {
        $this->key = $key;

        return $this;
    }

    public function getKey(): ?string
    {
        return $this->key;
    }

    public function setFamily(string $family): static
    {
        $this->family = $family;

        return $this;
    }

    public function getFamily(): ?string
    {
        return $this->family;
    }

    public function setCommon(bool $common): static
    {
        $this->common = $common;

        return $this;
    }

    public function isCommon(): ?bool
    {
        return $this->common;
    }

    public function addPart(Part $part): ?Piece
    {
        $part->setInstrument($this);
        $this->parts[] = $part;

        return $this;
    }

    public function removePart(Part $part)
    {
        $part->setInstrument(null);
        $this->parts->removeElement($part);
    }

    public function getParts(): Collection
    {
        return $this->parts;
    }

    public function __toString()
    {
        return $this->display;
    }

    public function setDisplay(string $display): static
    {
        $this->display = $display;

        return $this;
    }
}
