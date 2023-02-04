<?php

namespace App\Entity;

use App\Repository\PersonRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Table(name: 'people')]
#[ORM\Entity(repositoryClass: PersonRepository::class)]
class Person
{
    #[ORM\Column]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    private ?string $lastName = null;

    #[ORM\ManyToMany(targetEntity: Piece::class, mappedBy: 'composers')]
    private Collection $compositions;

    #[ORM\ManyToMany(targetEntity: Piece::class, mappedBy: 'arrangers')]
    private Collection $arrangements;


    public function __construct()
    {
        $this->compositions = new ArrayCollection();
        $this->arrangements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function addComposition(Piece $composition): static
    {
        $this->compositions[] = $composition;

        return $this;
    }

    public function removeComposition(Piece $composition): static
    {
        $this->compositions->removeElement($composition);

        return $this;
    }

    public function getCompositions(): Collection
    {
        return $this->compositions;
    }

    public function addArrangement(Piece $arrangement): static
    {
        $this->arrangements[] = $arrangement;

        return $this;
    }

    public function removeArrangement(Piece $arrangement): static
    {
        $this->arrangements->removeElement($arrangement);

        return $this;
    }

    public function getArrangements(): Collection
    {
        return $this->arrangements;
    }

    public function __toString()
    {
        return $this->firstName . ' ' . $this->lastName;
    }
}
