<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Traits\BlameableEntity;
use AppBundle\Traits\TimestampableEntity;
use AppBundle\Traits\SoftDeleteableEntity;
use Symfony\Component\Validator\Constraints AS Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Institution
 *
 * @ORM\Table(name="inv_institutions")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\InstitutionRepository")
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity(
 *     fields={"name", "shortName"},
 *     message="This name is already in use."
 * )
 */
class Institution
{
    use BlameableEntity, TimestampableEntity, SoftDeleteableEntity;

    /**
     * @ORM\Id
     * @ORM\Column(name="ID", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="NAME", type="string", length=255 , nullable=true)
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="SHORT_NAME", type="string", length=255 , nullable=true)
     * @Assert\NotBlank()
     */
    protected $shortName;

    /**
     * @var string
     *
     * @ORM\Column(name="WEB_URL", type="string", length=255 , nullable=true)
     */
    protected $webUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="NOTE", type="string", length=512 , nullable=true)
     */
    protected $note;

    /**
     * @ORM\Column(name="LOGO", type="string", length=255, nullable=true)
     */
    protected $logo;

    /**
     * @Assert\File(maxSize="5M")
     */
    public $logoFile;

    public $tempLogo;

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
     * @return mixed
     */
    public function getLogo()
    {
        return $this->logo === null ? 'assets/bank.png' : $this->getWebLogoPath();
    }

    /**
     * @param mixed $logo
     *
     * @return Institution
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getLogoFile()
    {
        return $this->logoFile;
    }

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setLogoFile(UploadedFile $file = null)
    {
        $this->logoFile = $file;
        if (isset($this->logo)) {
            $this->tempLogo = $this->logo;
        }
    }

    private function getRandomFileName()
    {
        return md5(uniqid(mt_rand(), true));
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->getLogoFile()) {
            $this->logo = $this->getRandomFileName() . '.' . $this->getLogoFile()->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null !== $this->getLogoFile()) {
            $this->getLogoFile()->move(
                $this->getUploadRootDir(),
                $this->logo
            );
            if (isset($this->tempLogo)) {
                @unlink($this->getUploadRootDir() . '/' . $this->tempLogo);
                $this->tempLogo = null;
            }
            $this->logoFile = null;
        }
    }

    public function getUploadRootDir()
    {
        return __DIR__ . '/../../../../web' . $this->getUploadDir();
    }

    public function getUploadDir()
    {
        return 'uploads/institutions';
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($file = $this->getAbsolutePath()) {
            @unlink($file);
        }
    }

    public function removeLogoFile($file)
    {
        $file_path = $this->getUploadRootDir().'/'.$file;

        if(file_exists($file_path)) unlink($file_path);
    }

    public function getAbsolutePath()
    {
        return null === $this->logo
            ? null
            : $this->getUploadRootDir() . '/' . $this->logo;
    }

    public function getWebLogoPath()
    {
        return null === $this->logo
            ? null
            : strpos($this->logo, 'http') === 0 ? $this->logo : $this->getUploadDir() . '/' . $this->logo;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getShortName()
    {
        return $this->shortName;
    }

    /**
     * @param string $shortName
     */
    public function setShortName($shortName)
    {
        $this->shortName = $shortName;
    }

    /**
     * @return string
     */
    public function getWebUrl()
    {
        return $this->webUrl;
    }

    /**
     * @param string $webUrl
     */
    public function setWebUrl($webUrl)
    {
        $this->webUrl = $webUrl;
    }

    /**
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @param string $note
     */
    public function setNote($note)
    {
        $this->note = $note;
    }
}