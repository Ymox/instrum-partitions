<?php

namespace YSoft\InstrumBundle\Entity;

/**
 * Status
 */
class Status
{
    /**
     * @var string
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
     * Set id
     *
     * @param string $id
     *
     * @return Status
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return string
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
     * @return Status
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
     * @return Status
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
