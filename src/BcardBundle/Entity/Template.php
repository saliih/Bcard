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
     * @Assert\File(maxSize="20M")
     */
    private $uploaded_file;
    /**
     * @var string
     *
     * @ORM\Column(name="picture", type="string", length=255, nullable=true)
     */
    private $picture;
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;


    /**
     * @Assert\File(maxSize="20M")
     */
    private $uploaded_file_recto;
    /**
     * @var string
     *
     * @ORM\Column(name="recto", type="string", length=255, nullable=true)
     */
    private $recto;

    /**
     * @var string
     *
     * @ORM\Column(name="verso", type="string", length=255, nullable=true)
     */
    private $verso;
    /**
     * @ORM\ManyToMany(targetEntity="BcardBundle\Entity\Fonts")
     * @ORM\JoinTable(name="Template_fonts",
     *      joinColumns={@ORM\JoinColumn(name="template_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="fonts_id", referencedColumnName="id")}
     * )
     */
    private $fonts;
    /**
     * @Assert\File(maxSize="20M")
     */
    private $uploaded_file_verso;

    public function upload($target_path,$type="")
    {

        Switch($type){
            case 'recto':
                if (null === $this->getUploadedFileRecto()) {
                    return;
                }                break;
            case "verso":
                if (null === $this->getUploadedFileVerso()) {
                    return;
                }                break;
            default:
                if (null === $this->getUploadedFile()) {
                    return;
                }

        }

        $fs = new Filesystem();
        if (!$fs->exists($target_path)) {
            $fs->mkdir($target_path, 0777);
        }
        $suffix = "";
        Switch($type){
            case 'recto':
                while (true) {
                    $originalname = str_replace(" ","",$this->getUploadedFileRecto()->getClientOriginalName());
                    $target_filename = pathinfo($originalname, PATHINFO_FILENAME) . $suffix . "." . $this->getUploadedFileRecto()->getClientOriginalExtension();
                    if (!file_exists($target_path . "/" . $target_filename)) {
                        break;
                    }
                    $suffix += 1;
                }

                $this->getUploadedFileRecto()->move(
                    $target_path,
                    $target_filename
                );
                $this->setRecto($target_filename);
                /*$image = new \Imagick();
                $image->readImageBlob(file_get_contents($target_path.$target_filename));
                $image->setImageFormat("png24");
                $image->resizeImage(1024, 768, \Imagick::FILTER_LANCZOS, 1);
                $target_filename = substr($target_filename,0-3)."png";
                $image->writeImage($target_path.$target_filename);

                $this->setPicture($target_filename);*/


                break;
            case "verso":
                while (true) {
                    $originalname = str_replace(" ","",$this->getUploadedFileVerso()->getClientOriginalName());
                    $target_filename = pathinfo($originalname, PATHINFO_FILENAME) . $suffix . "." . $this->getUploadedFileVerso()->getClientOriginalExtension();
                    if (!file_exists($target_path . "/" . $target_filename)) {
                        break;
                    }
                    $suffix += 1;
                }
                $this->getUploadedFileVerso()->move(
                    $target_path,
                    $target_filename
                );
                $this->setVerso($target_filename);
                break;
            default:
                while (true) {
                    $originalname = str_replace(" ","",$this->getUploadedFile()->getClientOriginalName());
                    $target_filename = pathinfo($originalname, PATHINFO_FILENAME) . $suffix . "." . $this->getUploadedFile()->getClientOriginalExtension();
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
                break;

        }

    }
    /**
     * Set file
     *
     * @param string $file
     *
     * @return Files
     */
    public function setUploadedFileRecto($file)
    {
        $this->uploaded_file_recto = $file;
        return $this;
    }

    /**
     * Get file
     *
     * @return string
     */
    public function getUploadedFileRecto()
    {
        return $this->uploaded_file_recto;
    }
    /**
     * Set file
     *
     * @param string $file
     *
     * @return Files
     */
    public function setUploadedFileVerso($file)
    {
        $this->uploaded_file_verso = $file;
        return $this;
    }

    /**
     * Get file
     *
     * @return string
     */
    public function getUploadedFileVerso()
    {
        return $this->uploaded_file_verso;
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
        return (string)$this->name;
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
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->fonts = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add font
     *
     * @param \BcardBundle\Entity\Fonts $font
     *
     * @return Template
     */
    public function addFont(\BcardBundle\Entity\Fonts $font)
    {
        $this->fonts[] = $font;

        return $this;
    }

    /**
     * Remove font
     *
     * @param \BcardBundle\Entity\Fonts $font
     */
    public function removeFont(\BcardBundle\Entity\Fonts $font)
    {
        $this->fonts->removeElement($font);
    }

    /**
     * Get fonts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFonts()
    {
        return $this->fonts;
    }
}
