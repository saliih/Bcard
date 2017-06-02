<?php

namespace BcardBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

/**
 * Template
 *
 * @ORM\Table(name="template")
 * @ORM\Entity(repositoryClass="BcardBundle\Repository\TemplateRepository")
 */
class Template
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;
    /**
     * @Assert\File(maxSize="20M")
     */
    private $uploaded_file;
    /**
     * @var string
     *
     * @ORM\Column(name="picture", type="string", length=255)
     */
    private $picture;

    /**
     * @var string
     *
     * @ORM\Column(name="recto", type="text", length=10000, nullable=true)
     */
    private $recto;

    /**
     * @var string
     *
     * @ORM\Column(name="verso", type="text", length=10000, nullable=true)
     */
    private $verso;

    public function upload($target_path)
    {
        if (null === $this->getUploadedFile()) {
            return;
        }
        $fs = new Filesystem();
        if (!$fs->exists($target_path)) {
            $fs->mkdir($target_path, 0777);
        }
        $suffix = "";
        while (true) {
            $target_filename = pathinfo($this->getUploadedFile()->getClientOriginalName(), PATHINFO_FILENAME) . $suffix . "." . $this->getUploadedFile()->getClientOriginalExtension();
            if (!file_exists($target_path . "/" . $target_filename)) {
                break;
            }
            $suffix += 1;
        }
        $this->getUploadedFile()->move(
            $target_path,
            $target_filename
        );
        $this->setPicture($target_filename);
    }
    /**
     * Set file
     *
     * @param string $file
     *
     * @return Files
     */
    public function setUploadedFile($file)
    {
        $this->uploaded_file = $file;
        return $this;
    }

    /**
     * Get file
     *
     * @return string
     */
    public function getUploadedFile()
    {
        return $this->uploaded_file;
    }

    public function __toString()
    {
        return $this->name;
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
     * Set name
     *
     * @param string $name
     *
     * @return Template
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
     * Set picture
     *
     * @param string $picture
     *
     * @return Template
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * Get picture
     *
     * @return string
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Set recto
     *
     * @param string $recto
     *
     * @return Template
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
     * @return Template
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
}
