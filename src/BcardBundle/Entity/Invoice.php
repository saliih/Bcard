<?php

namespace BcardBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Invoice
 *
 * @ORM\Table(name="invoice")
 * @ORM\Entity(repositoryClass="BcardBundle\Repository\InvoiceRepository")
 */
class Invoice
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255,nullable=true)
     */
    private $name;
    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255,nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="tel", type="string", length=20,nullable=true)
     */
    private $tel;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="text",nullable=true)
     */
    private $adresse;

    /**
     * @var string
     *
     * @ORM\Column(name="recto", type="text",nullable=true)
     */
    private $recto;

    /**
     * @var string
     *
     * @ORM\Column(name="verso", type="text",nullable=true)
     */
    private $verso;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dcr", type="datetime",nullable=true)
     */
    private $dcr;

    /**
     * @var bool
     *
     * @ORM\Column(name="act", type="boolean", nullable=true)
     */
    private $act;

    public function __construct()
    {
        $this->act= false;
        $this->dcr = new \DateTime();
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
     * @return Invoice
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
     * Set tel
     *
     * @param string $tel
     *
     * @return Invoice
     */
    public function setTel($tel)
    {
        $this->tel = $tel;

        return $this;
    }

    /**
     * Get tel
     *
     * @return string
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     *
     * @return Invoice
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set recto
     *
     * @param string $recto
     *
     * @return Invoice
     */
    public function setRecto($recto)
    {
        $this->recto = $recto;

        return $this;
    }

    /**
     * Get recto
     *
     * @return string
     */
    public function getRecto()
    {
        return $this->recto;
    }

    /**
     * Set verso
     *
     * @param string $verso
     *
     * @return Invoice
     */
    public function setVerso($verso)
    {
        $this->verso = $verso;

        return $this;
    }

    /**
     * Get verso
     *
     * @return string
     */
    public function getVerso()
    {
        return $this->verso;
    }

    /**
     * Set dcr
     *
     * @param \DateTime $dcr
     *
     * @return Invoice
     */
    public function setDcr($dcr)
    {
        $this->dcr = $dcr;

        return $this;
    }

    /**
     * Get dcr
     *
     * @return \DateTime
     */
    public function getDcr()
    {
        return $this->dcr;
    }

    /**
     * Set act
     *
     * @param boolean $act
     *
     * @return Invoice
     */
    public function setAct($act)
    {
        $this->act = $act;

        return $this;
    }

    /**
     * Get act
     *
     * @return bool
     */
    public function getAct()
    {
        return $this->act;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }
}

