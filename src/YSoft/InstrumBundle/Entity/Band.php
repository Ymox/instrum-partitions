<?php

namespace YSoft\InstrumBundle\Entity;

/**
 * Band
 */
class Band
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
    private $lendings;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->lendings = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Band
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
     * Add lending
     *
     * @param \YSoft\InstrumBundle\Entity\Lending $lending
     *
     * @return Band
     */
    public function addLending(\YSoft\InstrumBundle\Entity\Lending $lending)
    {
        $this->lendings[] = $lending;

        return $this;
    }

    /**
     * Remove lending
     *
     * @param \YSoft\InstrumBundle\Entity\Lending $lending
     */
    public function removeLending(\YSoft\InstrumBundle\Entity\Lending $lending)
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

    public function __toString()
    {
        return $this->name;
    }
}
