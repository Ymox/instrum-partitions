<?php

namespace App\Entity;

use App\Config\Location;
use App\Config\State;
use App\Repository\PieceRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Table]
#[ORM\Entity(repositoryClass: PieceRepository::class)]
#[UniqueEntity(fields: ['name'])]
class Piece
{
    #[ORM\Column]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?float $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $translation = null;

    #[ORM\Column(length: 255, enumType: Location::class, nullable: true)]
    private ?Location $location = null;

    #[ORM\Column]
    private ?int $states = null;

    #[ORM\Column(nullable: true)]
    private ?float $level = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $year = null;

    #[ORM\Column(length: 255, nullable: true, unique: true)]
    private ?string $reference = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $note = null;

    #[ORM\Column]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTime $createdAt = null;

    #[ORM\OneToMany(targetEntity: Piece::class, mappedBy: 'work', cascade: ['persist'])]
    private Collection $movements;

    #[ORM\OneToMany(targetEntity: Part::class, mappedBy: 'piece', cascade: ['persist', 'remove'], orphanRemoval: true, fetch: 'EXTRA_LAZY')]
    #[ORM\OrderBy(['solo' => 'DESC', 'instrument' => 'ASC', 'number' => 'ASC'])]
    private Collection $parts;

    #[ORM\OneToMany(targetEntity: Missing::class, mappedBy: 'piece', cascade: ['persist', 'remove'], orphanRemoval: true, fetch: 'EXTRA_LAZY')]
    private Collection $missings;

    #[ORM\ManyToOne(targetEntity: Publisher::class, inversedBy: 'pieces', cascade: ['persist', 'remove'])]
    private ?Publisher $publisher = null;

    #[ORM\JoinColumn(name: 'size', referencedColumnName: 'name')]
    #[ORM\ManyToOne(targetEntity: Size::class, inversedBy: 'pieces', cascade: ['persist'])]
    private ?Size $size = null;

    #[ORM\ManyToOne(targetEntity: Instrumentation::class, inversedBy: 'pieces', cascade: ['persist'])]
    private ?Instrumentation $instrumentation = null;

    #[ORM\ManyToOne(targetEntity: Type::class, inversedBy: 'pieces', cascade: ['persist'])]
    private ?Type $type = null;

    #[ORM\ManyToOne(targetEntity: Program::class, inversedBy: 'pieces')]
    private ?Program $program = null;

    #[ORM\ManyToOne(targetEntity: Piece::class, inversedBy: 'movements')]
    private ?Piece $work = null;

    #[ORM\JoinTable(name: 'piece_composer')]
    #[ORM\ManyToMany(targetEntity: Person::class, inversedBy: 'compositions', cascade: ['persist'])]
    private Collection $composers;

    #[ORM\JoinTable(name: 'piece_arranger')]
    #[ORM\ManyToMany(targetEntity: Person::class, inversedBy: 'arrangements', cascade: ['persist'])]
    private Collection $arrangers;

    #[ORM\ManyToMany(targetEntity: Concert::class, mappedBy: 'pieces')]
    #[ORM\OrderBy(['date' => 'DESC'])]
    private Collection $concerts;

    #[ORM\ManyToMany(targetEntity: Lending::class, mappedBy: 'pieces')]
    #[ORM\OrderBy(['start' => 'DESC'])]
    private Collection $lendings;


    public static $STATES_LIST = [
        State::VERIFIED,
        State::SCANNED,
        State::STAMPED,
        State::COLOURED,
    ];

    public static $LOCATIONS_LIST = [
        Location::SERVER,
        Location::STOWED,
        Location::SHELF,
        Location::LENT,
        Location::RETURNED,
        Location::LOST,
    ];

    public function __construct()
    {
        $this->movements = new ArrayCollection();
        $this->parts = new ArrayCollection();
        $this->missings = new ArrayCollection();
        $this->composers = new ArrayCollection();
        $this->arrangers = new ArrayCollection();
        $this->concerts = new ArrayCollection();
        $this->lendings = new ArrayCollection();
    }

    public function getId(): ?float
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

    public function setTranslation(string $translation): static
    {
        $this->translation = $translation;

        return $this;
    }

    public function getTranslation(): ?string
    {
        return $this->translation;
    }

    public function setLocation(?Location $location = null): static
    {
        $this->location = $location;

        return $this;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setStates(int $states = null): static
    {
        $this->states = $states;

        return $this;
    }

    public function hasState(int|State $state = null): ?bool
    {
        if ($state instanceof State) {
            $value = $state->value;
        } else {
            $value = $state;
        }

        return ($this->states & $value) === $value;
    }

    public function hasAnyState(int $states = null): ?bool
    {
        return ($this->states & $states) != 0;
    }

    public function addState(int $state = null): static
    {
        $this->states |= $state;

        return $this;
    }

    public function removeState(int $state = null): ?self
    {
        $this->states &= ~$state;

        return $this;
    }

    public function getStates(): ?int
    {
        return $this->states;
    }

    public function setLevel(float $level): static
    {
        $this->level = $level;

        return $this;
    }

    public function getLevel(): ?float
    {
        return $this->level;
    }

    public function setYear(int $year): static
    {
        $this->year = $year;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setReference(string $reference): static
    {
        $this->reference = $reference;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
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

    public function addMovement(Piece $movement): static
    {
        $movement->setWork($this);
        $this->movements[] = $movement;

        return $this;
    }

    public function removeMovement(Piece $movement)
    {
        $movement->setWork(null);
        $this->movements->removeElement($movement);
    }

    public function getMovements(): Collection
    {
        return $this->movements;
    }

    public function addPart(Part $part): static
    {
        $part->setPiece($this);
        $this->parts[] = $part;

        return $this;
    }

    public function removePart(Part $part): static
    {
        $part->setPiece(null);
        $this->parts->removeElement($part);

        return $this;
    }

    public function getParts(): Collection
    {
        return $this->parts;
    }

    public function addMissing(Missing $missing): static
    {
        $missing->setPiece($this);
        $this->missings[] = $missing;

        return $this;
    }

    public function removeMissing(Missing $missing): static
    {
        $this->missings->removeElement($missing);

        return $this;
    }

    public function getMissings(): Collection
    {
        return $this->missings;
    }

    public function setPublisher(Publisher $publisher = null): static
    {
        $this->publisher = $publisher;

        return $this;
    }

    public function getPublisher(): ?Publisher
    {
        return $this->publisher;
    }

    public function setSize(Size $size = null): static
    {
        $this->size = $size;

        return $this;
    }

    public function getSize(): ?Size
    {
        return $this->size;
    }

    public function setInstrumentation(Instrumentation $instrumentation = null): ?self
    {
        $this->instrumentation = $instrumentation;

        return $this;
    }

    public function getInstrumentation(): ?Instrumentation
    {
        return $this->instrumentation;
    }

    public function setType(Type $type = null): static
    {
        $this->type = $type;

        return $this;
    }

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setProgram(Program $program = null): static
    {
        $this->program = $program;

        return $this;
    }

    public function getProgram(): ?Program
    {
        return $this->program;
    }

    public function setWork(Piece $work = null): static
    {
        $this->work = $work;

        return $this;
    }

    public function getWork(): ?Piece
    {
        return $this->work;
    }

    public function addComposer(Person $composer): static
    {
        $this->composers[] = $composer;

        return $this;
    }

    public function removeComposer(Person $composer): static
    {
        $this->composers->removeElement($composer);

        return $this;
    }

    public function getComposers(): Collection
    {
        return $this->composers;
    }

    public function addArranger(Person $arranger): static
    {
        $this->arrangers[] = $arranger;

        return $this;
    }

    public function removeArranger(Person $arranger): static
    {
        $this->arrangers->removeElement($arranger);

        return $this;
    }

    public function getArrangers(): Collection
    {
        return $this->arrangers;
    }

    public function addConcert(Concert $concert): static
    {
        $this->concerts[] = $concert;

        return $this;
    }

    public function removeConcert(Concert $concert): static
    {
        $this->concerts->removeElement($concert);

        return $this;
    }

    public function getConcerts(): Collection
    {
        return $this->concerts;
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

    public function setCreatedAt(\DateTime $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }
}
