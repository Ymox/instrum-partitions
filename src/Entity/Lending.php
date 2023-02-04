<?php

namespace App\Entity;

use App\Repository\LendingRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Table(name: 'lending')]
#[ORM\Entity(repositoryClass: LendingRepository::class)]
class Lending
{
    #[ORM\Column]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $contact = null;

    #[ORM\Column(type: 'date')]
    private ?\DateTime $start = null;

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTime $end = null;

    #[ORM\Column(type: 'boolean')]
    private ?string $ours = null;

    #[ORM\ManyToOne(targetEntity: Band::class, inversedBy: 'lendings')]
    private ?Band $band = null;

    #[ORM\JoinTable(name: 'piece_move')]
    #[ORM\ManyToMany(targetEntity: Piece::class, inversedBy: 'lendings', cascade: ['persist'])]
    private Collection $pieces;


    public function __construct()
    {
        $this->pieces = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setContact(string $contact): static
    {
        $this->contact = $contact;

        return $this;
    }

    public function getContact(): ?string
    {
        return $this->contact;
    }

    public function setStart(\DateTime $start): static
    {
        $this->start = $start;

        return $this;
    }

    public function getStart(): ?\DateTime
    {
        return $this->start;
    }

    public function setEnd(\DateTime $end): static
    {
        $this->end = $end;

        return $this;
    }

    public function getEnd(): ?\DateTime
    {
        return $this->end;
    }

    public function setOurs(string $ours): static
    {
        $this->ours = $ours;

        return $this;
    }

    public function isOurs(): ?string
    {
        return $this->ours;
    }

    public function setBand(Band $band = null): static
    {
        $this->band = $band;

        return $this;
    }

    public function getBand(): ?Band
    {
        return $this->band;
    }

    public function addPiece(Piece $piece): static
    {
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
