<?php

namespace App\Entity;

use App\Repository\ConcertRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Table(name: 'concert')]
#[ORM\Entity(repositoryClass: ConcertRepository::class)]
#[UniqueEntity(fields: ['name'])]
class Concert
{
    #[ORM\Column]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $name = null;

    #[ORM\Column]
    private ?\DateTime $date = null;

    #[ORM\JoinColumn(name: 'program_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: Piece::class, inversedBy: 'concerts', cascade: ['persist', 'remove'])]
    private Collection $pieces;


    public function __construct()
    {
        $this->pieces = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setName(string $name): ?Concert
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setDate(\DateTime $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function addPiece(Piece $piece): static
    {
        $piece->addConcert($this);
        $this->pieces[] = $piece;

        return $this;
    }

    public function removePiece(Piece $piece): static
    {
        $this->pieces->removeElement($piece);

        return $this;
    }

    public function getPieces(): Collection
    {
        return $this->pieces;
    }
}
