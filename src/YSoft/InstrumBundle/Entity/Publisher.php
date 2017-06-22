<?php

namespace YSoft\InstrumBundle\Entity;

/**
 * Publisher
 */
class Publisher
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $note;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $pieces;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pieces = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
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
     * @return Publisher
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
     * Set note
     *
     * @param string $note
     *
     * @return Publisher
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
     * Add piece
     *
     * @param \YSoft\InstrumBundle\Entity\Piece $piece
     *
     * @return Publisher
     */
    public function addPiece(\YSoft\InstrumBundle\Entity\Piece $piece)
    {
        $this->pieces[] = $piece;

        return $this;
    }

    /**
     * Remove piece
     *
     * @param \YSoft\InstrumBundle\Entity\Piece $piece
     */
    public function removePiece(\YSoft\InstrumBundle\Entity\Piece $piece)
    {
        $this->pieces->removeElement($piece);
    }

    /**
     * Get pieces
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPieces()
    {
        return $this->pieces;
    }

    public function __toString()
    {
        return $this->name;
    }
}