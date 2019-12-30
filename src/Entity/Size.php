<?php

namespace App\Entity;

/**
 * Size
 */
class Size
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var integer
     */
    private $minWidth;

    /**
     * @var integer
     */
    private $maxWidth;

    /**
     * @var integer
     */
    private $minHeight;

    /**
     * @var integer
     */
    private $maxHeight;

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
     * Set name
     *
     * @param string $name
     *
     * @return Size
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
     * Set minWidth
     *
     * @param integer $minWidth
     *
     * @return Size
     */
    public function setMinWidth($minWidth)
    {
        $this->minWidth = $minWidth;

        return $this;
    }

    /**
     * Get minWidth
     *
     * @return integer
     */
    public function getMinWidth()
    {
        return $this->minWidth;
    }

    /**
     * Set maxWidth
     *
     * @param integer $maxWidth
     *
     * @return Size
     */
    public function setMaxWidth($maxWidth)
    {
        $this->maxWidth = $maxWidth;

        return $this;
    }

    /**
     * Get maxWidth
     *
     * @return integer
     */
    public function getMaxWidth()
    {
        return $this->maxWidth;
    }

    /**
     * Set minHeight
     *
     * @param integer $minHeight
     *
     * @return Size
     */
    public function setMinHeight($minHeight)
    {
        $this->minHeight = $minHeight;

        return $this;
    }

    /**
     * Get minHeight
     *
     * @return integer
     */
    public function getMinHeight()
    {
        return $this->minHeight;
    }

    /**
     * Set maxHeight
     *
     * @param integer $maxHeight
     *
     * @return Size
     */
    public function setMaxHeight($maxHeight)
    {
        $this->maxHeight = $maxHeight;

        return $this;
    }

    /**
     * Get maxHeight
     *
     * @return integer
     */
    public function getMaxHeight()
    {
        return $this->maxHeight;
    }

    /**
     * Set note
     *
     * @param string $note
     *
     * @return Size
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
     * @param \App\Entity\Piece $piece
     *
     * @return Size
     */
    public function addPiece(\App\Entity\Piece $piece)
    {
        $this->pieces[] = $piece;

        return $this;
    }

    /**
     * Remove piece
     *
     * @param \App\Entity\Piece $piece
     */
    public function removePiece(\App\Entity\Piece $piece)
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

    public function getMaxDimension()
    {
        return $this->maxWidth . '×' . $this->maxHeight;
    }

    public function getMinDimension()
    {
        if ($this->minWidth) {
            $min = $this->minWidth;
        } else {
            $min = $this->maxWidth;
        }
        $min .= '×';
        if ($this->minHeight) {
            $min .= $this->minHeight;
        } else {
            $min .= $this->maxHeight;
        }

        return $min;
    }
}
