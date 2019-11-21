<?php

namespace App\Entity;

/**
 * Instrument
 */
class Instrument
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
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $family;

    /**
     * @var boolean
     */
    public $common;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $parts;

    /**
     * NOT A PERSISTED PROPERTY
     * @var string
     */
    private $display = '';

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->parts = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return self
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
     * Set key
     *
     * @param string $key
     *
     * @return self
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Get key
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Set family
     *
     * @param string $family
     *
     * @return self
     */
    public function setFamily($family)
    {
        $this->family = $family;

        return $this;
    }

    /**
     * Get family
     *
     * @return string
     */
    public function getFamily()
    {
        return $this->family;
    }

    /**
     * Set common
     *
     * @param boolean $common
     *
     * @return self
     */
    public function setCommon($common)
    {
        $this->common = $common;

        return $this;
    }

    /**
     * Get common
     *
     * @return boolean
     */
    public function isCommon()
    {
        return $this->common;
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
        $part->setInstrument($this);
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
        $part->setInstrument(null);
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

    public function __toString()
    {
        return $this->display;
    }

    /**
     * Set display
     *
     * @param string $display
     *
     * @return self
     */
    public function setDisplay(string $display)
    {
        $this->display = $display;

        return $this;
    }
}
