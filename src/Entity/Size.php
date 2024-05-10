<?php

namespace App\Entity;

use App\Repository\SizeRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;

#[ORM\Table(name: 'size')]
#[ORM\Entity(repositoryClass: SizeRepository::class)]
class Size
{
    #[ORM\Column(length: 127)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    private ?string $name = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $minWidth = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $maxWidth = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $minHeight = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $maxHeight = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $note = null;

    #[ORM\OneToMany(targetEntity: Piece::class, mappedBy: 'size')]
    private Collection $pieces;


    public function __construct()
    {
        $this->pieces = new ArrayCollection();
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

    public function setMinWidth(int $minWidth): static
    {
        $this->minWidth = $minWidth;

        return $this;
    }

    public function getMinWidth(): ?int
    {
        return $this->minWidth;
    }

    public function setMaxWidth(int $maxWidth): static
    {
        $this->maxWidth = $maxWidth;

        return $this;
    }

    public function getMaxWidth(): ?int
    {
        return $this->maxWidth;
    }

    public function setMinHeight(int $minHeight): static
    {
        $this->minHeight = $minHeight;

        return $this;
    }

    public function getMinHeight(): ?int
    {
        return $this->minHeight;
    }

    public function setMaxHeight(int $maxHeight): static
    {
        $this->maxHeight = $maxHeight;

        return $this;
    }

    public function getMaxHeight(): ?int
    {
        return $this->maxHeight;
    }

    public function setNote(string $note): static
    {
        $this->note = $note;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function addPiece(Piece $piece): static
    {
        $this->pieces[] = $piece;

        return $this;
    }

    public function removePiece(Piece $piece)
    {
        $this->pieces->removeElement($piece);
    }

    public function getPieces(): Collection
    {
        return $this->pieces;
    }

    public function getMaxDimension()
    {
        return $this->maxWidth . '×' . $this->maxHeight;
    }

    public function getMinDimension()
    {
        if ($this->minWidth) {
            $min = $this->minWidth;
        } else {
            $min = $this->maxWidth;
        }
        $min .= '×';
        if ($this->minHeight) {
            $min .= $this->minHeight;
        } else {
            $min .= $this->maxHeight;
    }

        return $min;
    }
}
