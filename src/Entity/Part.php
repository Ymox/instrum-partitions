<?php

namespace App\Entity;

use App\Repository\PartRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Table(name: 'part')]
#[ORM\Entity(repositoryClass: PartRepository::class)]
#[UniqueEntity(['piece', 'instrument', 'clef', 'number', 'solo'], errorPath: 'instrument', ignoreNull: false)]
class Part
{
    const TRANSLATE_DISPLAY = 'display';

    const TRANSLATE_DOWNLOAD = 'download';

    #[ORM\Column]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id = null;

    #[ORM\Column(length: 3, nullable: true)]
    private ?string $clef = null;

    #[ORM\Column(nullable: true)]
    private ?int $number = null;

    #[ORM\Column]
    private ?bool $solo = null;

    #[ORM\Column(nullable: true)]
    private ?string $file = null;

    #[ORM\JoinColumn(name: 'instrument_id', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: Instrument::class, inversedBy: 'parts')]
    private ?Instrument $instrument = null;

    #[ORM\JoinColumn(name: 'piece_id', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: Piece::class, inversedBy: 'parts')]
    private ?Piece $piece = null;

    // NOT A PERSISTED PROPERTY
    private ?\SplFileInfo $upload = null;

    // NOT A PERSISTED PROPERTY
    private string $downloadFolder = '';

    // NOT A PERSISTED PROPERTY
    private string $display = '';

    // NOT A PERSISTED PROPERTY
    private string $downloadName = '';


    public function getId(): ?int
    {
        return $this->id;
    }

    public function setClef(string $clef): static
    {
        $this->clef = $clef;

        return $this;
    }

    public function getClef(): ?string
    {
        return $this->clef;
    }

    public function setNumber(int $number): static
    {
        $this->number = $number;

        return $this;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setSolo(bool $solo): static
    {
        $this->solo = $solo;

        return $this;
    }

    public function isSolo(): ?bool
    {
        return $this->solo;
    }

    public function setFile($file = null): static
    {
        $this->file = $file;

        return $this;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setPiece(?Piece $piece): static
    {
        $this->piece = $piece;

        return $this;
    }

    public function getPiece(): ?Piece
    {
        return $this->piece;
    }

    public function setInstrument(?Instrument $instrument): static
    {
        $this->instrument = $instrument;

        return $this;
    }

    public function getInstrument(): ?Instrument
    {
        return $this->instrument;
    }

    public function __toString()
    {
        return $this->display;
    }

    public function setUpload(?\SplFileInfo $upload = null): static
    {
        $this->upload = $upload;

        return $this;
    }

    public function getUpload(): ?\SplFileInfo
    {
        if ($this->upload === null && $this->file && $this->downloadFolder && is_file($filePath = rtrim($this->downloadFolder, '/\\') . '/' . $this->file)) {
            $this->upload = new \SplFileObject($filePath);
        }

        return $this->upload;
    }

    public function setDownloadFolder(string $downloadFolder): ?self
    {
        $this->downloadFolder = $downloadFolder;

        return $this;
    }

    public function getDownloadFolder(): ?string
    {
        return $this->downloadFolder;
    }

    public function getDownloadPath(): ?string
    {
        return $this->downloadFolder . '/' . $this->file;
    }

    public function setDisplay(string $display): static
    {
        $this->display = $display;

        return $this;
    }

    public function setDownloadName(string $downloadName): static
    {
        $this->downloadName = $downloadName;

        return $this;
    }

    public function getDownloadName(): ?string
    {
        return $this->downloadName;
    }
}
