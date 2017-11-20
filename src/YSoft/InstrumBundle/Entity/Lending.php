<?php

namespace YSoft\InstrumBundle\Entity;

/**
 * Lending
 */
class Lending
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $contact;

    /**
     * @var \DateTime
     */
    private $start;

    /**
     * @var \DateTime
     */
    private $end;

    /**
     * @var string
     */
    private $ours;

    /**
     * @var \YSoft\InstrumBundle\Entity\Band
     */
    private $band;

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
     * Set contact
     *
     * @param string $contact
     *
     * @return Lending
     */
    public function setContact($contact)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * Get contact
     *
     * @return string
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * Set start
     *
     * @param \DateTime $start
     *
     * @return Lending
     */
    public function setStart($start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * Get start
     *
     * @return \DateTime
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Set end
     *
     * @param \DateTime $end
     *
     * @return Lending
     */
    public function setEnd($end)
    {
        $this->end = $end;

        return $this;
    }

    /**
     * Get end
     *
     * @return \DateTime
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * Set ours
     *
     * @param string $ours
     *
     * @return Lending
     */
    public function setOurs($ours)
    {
        $this->ours = $ours;

        return $this;
    }

    /**
     * Is ours
     *
     * @return string
     */
    public function isOurs()
    {
        return $this->ours;
    }

    /**
     * Set band
     *
     * @param \YSoft\InstrumBundle\Entity\Band $band
     *
     * @return Lending
     */
    public function setBand(\YSoft\InstrumBundle\Entity\Band $band = null)
    {
        $this->band = $band;

        return $this;
    }

    /**
     * Get band
     *
     * @return \YSoft\InstrumBundle\Entity\Band
     */
    public function getBand()
    {
        return $this->band;
    }

    /**
     * Add piece
     *
     * @param \YSoft\InstrumBundle\Entity\Piece $piece
     *
     * @return Lending
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
