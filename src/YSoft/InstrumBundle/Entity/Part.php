<?php

namespace YSoft\InstrumBundle\Entity;

/**
 * Part
 */
class Part
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $clef;

    /**
     * @var int
     */
    private $number;

    /**
     * @var boolean
     */
    private $solo;

    /**
     * @var string
     */
    private $file;

    /**
     * @var \YSoft\InstrumBundle\Entity\Piece
     */
    public $piece;

    /**
     * @var \YSoft\InstrumBundle\Entity\instrument
     */
    public $instrument;

    /**
     * NOT A PERSISTED PROPERTY
     * @var \Symfony\Component\HttpFoundation\File\UploadedFile
     */
    private $upload;

    /**
     * NOT A PERSISTED PROPERTY
     * @var string
     */
    private $downloadFolder;


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
     * Set clef
     *
     * @param string $clef
     *
     * @return self
     */
    public function setClef($clef)
    {
        $this->clef = $clef;

        return $this;
    }

    /**
     * Get clef
     *
     * @return string
     */
    public function getClef()
    {
        return $this->clef;
    }

    /**
     * Set number
     *
     * @param integer $number
     *
     * @return self
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return int
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set solo
     *
     * @param boolean $solo
     *
     * @return self
     */
    public function setSolo($solo)
    {
        $this->solo = $solo;

        return $this;
    }

    /**
     * Get solo
     *
     * @return boolean
     */
    public function isSolo()
    {
        return $this->solo;
    }

    /**
     * Set file
     *
     * @param string $file
     *
     * @return self
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set piece
     *
     * @param \YSoft\InstrumBundle\Entity\Piece $piece
     *
     * @return self
     */
    public function setPiece(\YSoft\InstrumBundle\Entity\Piece $piece)
    {
        $this->piece = $piece;

        return $this;
    }

    /**
     * Get piece
     *
     * @return int
     */
    public function getPiece()
    {
        return $this->piece;
    }

    /**
     * Set instrument
     *
     * @param \YSoft\InstrumBundle\Entity\Instrument $instrument
     *
     * @return self
     */
    public function setInstrument(\YSoft\InstrumBundle\Entity\Instrument $instrument)
    {
        $this->instrument = $instrument;

        return $this;
    }

    /**
     * Get instrument
     *
     * @return int
     */
    public function getInstrument()
    {
        return $this->instrument;
    }

    /**
     * Set upload
     *
     * @param \Symfony\Component\HttpFoundation\File\File $upload
     *
     * @return self
     */
    public function setUpload(\Symfony\Component\HttpFoundation\File\File $upload)
    {
        $this->upload = $upload;

        return $this;
    }

    /**
     * Get upload
     *
     * @return \Symfony\Component\HttpFoundation\File\File
     */
    public function getUpload()
    {
        return $this->upload;
    }

    /**
     * Set download folder
     *
     * @param string $downloadFolder
     *
     * @return self
     */
    public function setDownloadFolder(string $downloadFolder)
    {
        $this->downloadFolder = $downloadFolder;

        return $this;
    }

    /**
     * Get download folder
     *
     * @return string
     */
    public function getDownloadFolder()
    {
        return $this->downloadFolder;
    }

    /**
     * Get download path
     *
     * @return string
     */
    public function getDownloadPath()
    {
        return $this->downloadFolder . '/' . $this->file;
    }
}