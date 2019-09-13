<?php

namespace YSoft\InstrumBundle\Entity;

/**
 * Part
 */
class Part
{
    const TRANSLATE_DISPLAY = 'display';

    const TRANSLATE_DOWNLOAD = 'download';

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
    private $piece;

    /**
     * @var \YSoft\InstrumBundle\Entity\instrument
     */
    private $instrument;

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
     * NOT A PERSISTED PROPERTY
     * @var string
     */
    private $display = '';

    /**
     * NOT A PERSISTED PROPERTY
     * @var string
     */
    private $downloadName = '';


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

    public function __toString()
    {
        return $this->display;
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

    /**
     * Set download name
     *
     * @param string $downloadName
     *
     * @return self
     */
    public function setDownloadName(string $downloadName)
    {
        $this->downloadName = $downloadName;

        return $this;
    }

    /**
     * Get download name
     *
     * @return string
     */
    public function getDownloadName()
    {
        return $this->downloadName;
    }
}
