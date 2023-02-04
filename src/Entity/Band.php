<?php

namespace App\Entity;

use App\Repository\BandRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Table]
#[ORM\Entity(repositoryClass: BandRepository::class)]
#[UniqueEntity(fields: ['name'])]
class Band
{
    #[ORM\Column]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $name = null;

    #[ORM\OneToMany(targetEntity: Lending::class, mappedBy: 'band')]
    private Collection $lendings;


    public function __construct()
    {
        $this->lendings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function addLending(Lending $lending): static
    {
        $this->lendings[] = $lending;

        return $this;
    }

    public function removeLending(Lending $lending): static
    {
        $this->lendings->removeElement($lending);

        return $this;
    }

    public function getLendings(): Collection
    {
        return $this->lendings;
    }

    public function __toString()
    {
        return $this->name;
    }
}
