<?php

namespace YSoft\InstrumBundle\Entity;

/**
 * Piece
 */
class Piece
{
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
     * @var float
     */
    private $level;

    /**
     * @var \YSoft\InstrumBundle\Entity\Instrumentation
     */
    private $instrumentation;

    /**
     * @var \YSoft\InstrumBundle\Entity\Type
     */
    private $type;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $missings;

    /**
     * @var \YSoft\InstrumBundle\Entity\Publisher
     */
    private $publisher;

    /**
     * @var \YSoft\InstrumBundle\Entity\Size
     */
    private $size;

    /**
     * @var \YSoft\InstrumBundle\Entity\Status
     */
    private $status;

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
     * Constructor
     */
    public function __construct()
    {
        $this->missings = new \Doctrine\Common\Collections\ArrayCollection();
        $this->composers = new \Doctrine\Common\Collections\ArrayCollection();
        $this->arrangers = new \Doctrine\Common\Collections\ArrayCollection();
        $this->concerts = new \Doctrine\Common\Collections\ArrayCollection();
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

    /**
     * Add missing
     *
     * @param \YSoft\InstrumBundle\Entity\Missing $missing
     *
     * @return Piece
     */
    public function addMissing(\YSoft\InstrumBundle\Entity\Missing $missing)
    {
        $missing->setPiece($this);
        $this->missings[] = $missing;

        return $this;
    }

    /**
     * Remove missing
     *
     * @param \YSoft\InstrumBundle\Entity\Missing $missing
     */
    public function removeMissing(\YSoft\InstrumBundle\Entity\Missing $missing)
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
     * @param \YSoft\InstrumBundle\Entity\Publisher $publisher
     *
     * @return Piece
     */
    public function setPublisher(\YSoft\InstrumBundle\Entity\Publisher $publisher = null)
    {
        $this->publisher = $publisher;

        return $this;
    }

    /**
     * Get publisher
     *
     * @return \YSoft\InstrumBundle\Entity\Publisher
     */
    public function getPublisher()
    {
        return $this->publisher;
    }

    /**
     * Set size
     *
     * @param \YSoft\InstrumBundle\Entity\Size $size
     *
     * @return Piece
     */
    public function setSize(\YSoft\InstrumBundle\Entity\Size $size = null)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return \YSoft\InstrumBundle\Entity\Size
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set status
     *
     * @param \YSoft\InstrumBundle\Entity\Status $status
     *
     * @return Piece
     */
    public function setStatus(\YSoft\InstrumBundle\Entity\Status $status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return \YSoft\InstrumBundle\Entity\Status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Add composer
     *
     * @param \YSoft\InstrumBundle\Entity\Person $composer
     *
     * @return Piece
     */
    public function addComposer(\YSoft\InstrumBundle\Entity\Person $composer)
    {
        $this->composers[] = $composer;

        return $this;
    }

    /**
     * Remove composer
     *
     * @param \YSoft\InstrumBundle\Entity\Person $composer
     */
    public function removeComposer(\YSoft\InstrumBundle\Entity\Person $composer)
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
     * @param \YSoft\InstrumBundle\Entity\Person $arranger
     *
     * @return Piece
     */
    public function addArranger(\YSoft\InstrumBundle\Entity\Person $arranger)
    {
        $this->arrangers[] = $arranger;

        return $this;
    }

    /**
     * Remove arranger
     *
     * @param \YSoft\InstrumBundle\Entity\Person $arranger
     */
    public function removeArranger(\YSoft\InstrumBundle\Entity\Person $arranger)
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
     * Set instrumentation
     *
     * @param \YSoft\InstrumBundle\Entity\Instrumentation $instrumentation
     *
     * @return Piece
     */
    public function setInstrumentation(\YSoft\InstrumBundle\Entity\Instrumentation $instrumentation = null)
    {
        $this->instrumentation = $instrumentation;

        return $this;
    }

    /**
     * Get instrumentation
     *
     * @return \YSoft\InstrumBundle\Entity\Instrumentation
     */
    public function getInstrumentation()
    {
        return $this->instrumentation;
    }

    /**
     * Set type
     *
     * @param \YSoft\InstrumBundle\Entity\Type $type
     *
     * @return Piece
     */
    public function setType(\YSoft\InstrumBundle\Entity\Type $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \YSoft\InstrumBundle\Entity\Type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Add concert
     *
     * @param \YSoft\InstrumBundle\Entity\Concert $concert
     *
     * @return Piece
     */
    public function addConcert(\YSoft\InstrumBundle\Entity\Concert $concert)
    {
        $this->concerts[] = $concert;

        return $this;
    }

    /**
     * Remove concert
     *
     * @param \YSoft\InstrumBundle\Entity\Concert $concert
     */
    public function removeConcert(\YSoft\InstrumBundle\Entity\Concert $concert)
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
}
