<?php

namespace App\Entity;

/**
 * Piece
 */
class Piece
{
    const LOCATION_STOWED = 'stowed';

    const LOCATION_SERVER = 'server';

    const LOCATION_LENT = 'lent';

    const LOCATION_RETURNED = 'returned';

    const LOCATION_SHELF = 'shelf';

    const LOCATION_LOST = 'lost';

    const STATE_VERIFIED = 1;

    const STATE_STAMPED = 2;

    const STATE_COLOURED = 4;

    const STATE_SCANNED = 8;

    /**
     * @var float
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $translation;

    /**
     * @var int
     */
    private $location;

    /**
     * @var integer
     */
    private $states;

    /**
     * @var float
     */
    private $level;

    /**
     * @var integer
     */
    private $year;

    /**
     * @var string
     */
    private $reference;

    /**
     * @var string
     */
    private $note;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \App\Entity\Publisher
     */
    private $publisher;

    /**
     * @var \App\Entity\Size
     */
    private $size;

    /**
     * @var \App\Entity\Instrumentation
     */
    private $instrumentation;

    /**
     * @var \App\Entity\Type
     */
    private $type;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $program;

    /**
     * @var \App\Entity\Piece
     */
    private $work;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $movements;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $parts;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $missings;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $composers;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $arrangers;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $concerts;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $lendings;

    public static $STATES_LIST = [
        self::STATE_VERIFIED,
        self::STATE_SCANNED,
        self::STATE_STAMPED,
        self::STATE_COLOURED,
    ];

    public static $LOCATIONS_LIST = [
        self::LOCATION_SERVER,
        self::LOCATION_STOWED,
        self::LOCATION_SHELF,
        self::LOCATION_LENT,
        self::LOCATION_RETURNED,
        self::LOCATION_LOST,
    ];

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->movements = new \Doctrine\Common\Collections\ArrayCollection();
        $this->parts = new \Doctrine\Common\Collections\ArrayCollection();
        $this->missings = new \Doctrine\Common\Collections\ArrayCollection();
        $this->composers = new \Doctrine\Common\Collections\ArrayCollection();
        $this->arrangers = new \Doctrine\Common\Collections\ArrayCollection();
        $this->concerts = new \Doctrine\Common\Collections\ArrayCollection();
        $this->lendings = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return float
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Piece
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set translation
     *
     * @param string $translation
     *
     * @return Piece
     */
    public function setTranslation($translation)
    {
        $this->translation = $translation;

        return $this;
    }

    /**
     * Get translation
     *
     * @return string
     */
    public function getTranslation()
    {
        return $this->translation;
    }

    /**
     * Set location
     *
     * @param string $location
     *
     * @return Piece
     */
    public function setLocation(string $location = null)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set states
     *
     * @param integer $state
     *
     * @return Piece
     */
    public function setStates(int $states = null)
    {
        $this->states = $states;

        return $this;
    }

    /**
     * Has state
     *
     * @param integer $state
     *
     * @return Piece
     */
    public function hasState(int $state = null)
    {
        return ($this->states & $state) === $state;
    }

    /**
     * Has any state
     *
     * @param integer $states
     * @param bool $partial
     *
     * @return Piece
     */
    public function hasAnyState(int $states = null)
    {
        return ($this->states & $states) != 0;
    }

    /**
     * Add state
     *
     * @param integer $state
     *
     * @return Piece
     */
    public function addState(int $state = null)
    {
        $this->states |= $state;

        return $this;
    }

    /**
     * Add state
     *
     * @param integer $state
     *
     * @return Piece
     */
    public function removeState(int $state = null)
    {
        $this->states &= ~$state;

        return $this;
    }

    /**
     * Get states
     *
     * @return integer
     */
    public function getStates()
    {
        return $this->states;
    }

    /**
     * Set level
     *
     * @param float $level
     *
     * @return Piece
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return float
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set year
     *
     * @param integer $year
     *
     * @return Piece
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return integer
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set reference
     *
     * @param string $reference
     *
     * @return Piece
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference
     *
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Set note
     *
     * @param string $note
     *
     * @return Piece
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Add movement
     *
     * @param \App\Entity\Piece $movement
     *
     * @return Piece
     */
    public function addMovement(\App\Entity\Piece $movement)
    {
        $movement->setWork($this);
        $this->movements[] = $movement;

        return $this;
    }

    /**
     * Remove movement
     *
     * @param \App\Entity\Piece $movement
     */
    public function removeMovement(\App\Entity\Piece $movement)
    {
        $movement->setWork(null);
        $this->movements->removeElement($movement);
    }

    /**
     * Get movements
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMovements()
    {
        return $this->movements;
    }

    /**
     * Add part
     *
     * @param \App\Entity\Part $part
     *
     * @return Piece
     */
    public function addPart(\App\Entity\Part $part)
    {
        $part->setPiece($this);
        $this->parts[] = $part;

        return $this;
    }

    /**
     * Remove part
     *
     * @param \App\Entity\Part $part
     */
    public function removePart(\App\Entity\Part $part)
    {
        $part->sePiece(null);
        $this->parts->removeElement($part);
    }

    /**
     * Get parts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getParts()
    {
        return $this->parts;
    }

    /**
     * Add missing
     *
     * @param \App\Entity\Missing $missing
     *
     * @return Piece
     */
    public function addMissing(\App\Entity\Missing $missing)
    {
        $missing->setPiece($this);
        $this->missings[] = $missing;

        return $this;
    }

    /**
     * Remove missing
     *
     * @param \App\Entity\Missing $missing
     */
    public function removeMissing(\App\Entity\Missing $missing)
    {
        $this->missings->removeElement($missing);
    }

    /**
     * Get missings
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMissings()
    {
        return $this->missings;
    }

    /**
     * Set publisher
     *
     * @param \App\Entity\Publisher $publisher
     *
     * @return Piece
     */
    public function setPublisher(\App\Entity\Publisher $publisher = null)
    {
        $this->publisher = $publisher;

        return $this;
    }

    /**
     * Get publisher
     *
     * @return \App\Entity\Publisher
     */
    public function getPublisher()
    {
        return $this->publisher;
    }

    /**
     * Set size
     *
     * @param \App\Entity\Size $size
     *
     * @return Piece
     */
    public function setSize(\App\Entity\Size $size = null)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return \App\Entity\Size
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set instrumentation
     *
     * @param \App\Entity\Instrumentation $instrumentation
     *
     * @return Piece
     */
    public function setInstrumentation(\App\Entity\Instrumentation $instrumentation = null)
    {
        $this->instrumentation = $instrumentation;

        return $this;
    }

    /**
     * Get instrumentation
     *
     * @return \App\Entity\Instrumentation
     */
    public function getInstrumentation()
    {
        return $this->instrumentation;
    }

    /**
     * Set type
     *
     * @param \App\Entity\Type $type
     *
     * @return Piece
     */
    public function setType(\App\Entity\Type $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \App\Entity\Type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set program
     *
     * @param \App\Entity\Program $program
     *
     * @return Piece
     */
    public function setProgram(\App\Entity\Program $program = null)
    {
        $this->program = $program;

        return $this;
    }

    /**
     * Get program
     *
     * @return \App\Entity\Program
     */
    public function getProgram()
    {
        return $this->program;
    }

    /**
     * Set work
     *
     * @param \App\Entity\Piece $work
     *
     * @return Piece
     */
    public function setWork(\App\Entity\Piece $work = null)
    {
        $this->work = $work;

        return $this;
    }

    /**
     * Get work
     *
     * @return \App\Entity\Piece
     */
    public function getWork()
    {
        return $this->work;
    }

    /**
     * Add composer
     *
     * @param \App\Entity\Person $composer
     *
     * @return Piece
     */
    public function addComposer(\App\Entity\Person $composer)
    {
        $this->composers[] = $composer;

        return $this;
    }

    /**
     * Remove composer
     *
     * @param \App\Entity\Person $composer
     */
    public function removeComposer(\App\Entity\Person $composer)
    {
        $this->composers->removeElement($composer);
    }

    /**
     * Get composers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComposers()
    {
        return $this->composers;
    }

    /**
     * Add arranger
     *
     * @param \App\Entity\Person $arranger
     *
     * @return Piece
     */
    public function addArranger(\App\Entity\Person $arranger)
    {
        $this->arrangers[] = $arranger;

        return $this;
    }

    /**
     * Remove arranger
     *
     * @param \App\Entity\Person $arranger
     */
    public function removeArranger(\App\Entity\Person $arranger)
    {
        $this->arrangers->removeElement($arranger);
    }

    /**
     * Get arrangers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getArrangers()
    {
        return $this->arrangers;
    }

    /**
     * Add concert
     *
     * @param \App\Entity\Concert $concert
     *
     * @return Piece
     */
    public function addConcert(\App\Entity\Concert $concert)
    {
        $this->concerts[] = $concert;

        return $this;
    }

    /**
     * Remove concert
     *
     * @param \App\Entity\Concert $concert
     */
    public function removeConcert(\App\Entity\Concert $concert)
    {
        $this->concerts->removeElement($concert);
    }

    /**
     * Get concerts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getConcerts()
    {
        return $this->concerts;
    }

    /**
     * Add lending
     *
     * @param \App\Entity\Lending $lending
     *
     * @return Piece
     */
    public function addLending(\App\Entity\Lending $lending)
    {
        $this->lendings[] = $lending;

        return $this;
    }

    /**
     * Remove lending
     *
     * @param \App\Entity\Lending $lending
     */
    public function removeLending(\App\Entity\Lending $lending)
    {
        $this->lendings->removeElement($lending);
    }

    /**
     * Get lendings
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLendings()
    {
        return $this->lendings;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Piece
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}
