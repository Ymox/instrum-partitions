<?php

namespace YSoft\InstrumBundle\Entity;

/**
 * Type
 */
class Type
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;
    
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
     * @return int
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
     * @return Type
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
     * Add piece
     *
     * @param \YSoft\InstrumBundle\Entity\Piece $piece
     *
     * @return Type
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
}
