<?php

namespace App\Entity;

/**
 * Missing
 */
class Missing
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $voice;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \App\Entity\Piece
     */
    private $piece;


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
     * Set voice
     *
     * @param string $voice
     *
     * @return Missing
     */
    public function setVoice($voice)
    {
        $this->voice = $voice;

        return $this;
    }

    /**
     * Get voice
     *
     * @return string
     */
    public function getVoice()
    {
        return $this->voice;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Missing
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
     * Set piece
     *
     * @param \App\Entity\Piece $piece
     *
     * @return Missing
     */
    public function setPiece(\App\Entity\Piece $piece)
    {
        $this->piece = $piece;

        return $this;
    }

    /**
     * Get piece
     *
     * @return \App\Entity\Piece
     */
    public function getPiece()
    {
        return $this->piece;
    }
}
