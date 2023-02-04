<?php

namespace App\Entity;

use App\Repository\MissingRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Table(name: 'missing')]
#[ORM\Entity(repositoryClass: MissingRepository::class)]
class Missing
{
    #[ORM\Column]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $voice = null;

    #[ORM\Column(type: 'datetime')]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTime $createdAt = null;

    #[ORM\JoinColumn(name: 'piece_id', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: Piece::class, inversedBy: 'missings')]
    private ?Piece $piece = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setVoice(string $voice): static
    {
        $this->voice = $voice;

        return $this;
    }

    public function getVoice(): ?string
    {
        return $this->voice;
    }

    public function setCreatedAt(\DateTime $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setPiece(Piece $piece): static
    {
        $this->piece = $piece;

        return $this;
    }

    public function getPiece(): ?Piece
    {
        return $this->piece;
    }
}
