<?php

namespace App\Entity;

/**
 * Person
 */
class Person
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $lastName;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $compositions;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $arrangements;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->compositions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->arrangements = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Person
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Person
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Add composition
     *
     * @param \App\Entity\Piece $composition
     *
     * @return Person
     */
    public function addComposition(\App\Entity\Piece $composition)
    {
        $this->compositions[] = $composition;

        return $this;
    }

    /**
     * Remove composition
     *
     * @param \App\Entity\Piece $composition
     */
    public function removeComposition(\App\Entity\Piece $composition)
    {
        $this->compositions->removeElement($composition);
    }

    /**
     * Get compositions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCompositions()
    {
        return $this->compositions;
    }

    /**
     * Add arrangement
     *
     * @param \App\Entity\Piece $arrangement
     *
     * @return Person
     */
    public function addArrangement(\App\Entity\Piece $arrangement)
    {
        $this->arrangements[] = $arrangement;

        return $this;
    }

    /**
     * Remove arrangement
     *
     * @param \App\Entity\Piece $arrangement
     */
    public function removeArrangement(\App\Entity\Piece $arrangement)
    {
        $this->arrangements->removeElement($arrangement);
    }

    /**
     * Get arrangements
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getArrangements()
    {
        return $this->arrangements;
    }

    public function __toString()
    {
        return $this->firstName . ' ' . $this->lastName;
    }
}
