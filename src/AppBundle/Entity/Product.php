<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Traits\BlameableEntity;
use AppBundle\Traits\TimestampableEntity;
use AppBundle\Traits\SoftDeleteableEntity;
use Symfony\Component\Validator\Constraints AS Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Product
 *
 * @ORM\Table(name="inv_products")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Product
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
     */
    protected $name; // gold or platinum or silver

    /**
     * @var string
     *
     * @ORM\Column(name="CATEGORY", type="string", length=255 , nullable=true)
     */
    protected $category; // visa or master or qcash or american express

    /**
     * @var string
     *
     * @ORM\Column(name="TYPE", type="string", length=255 , nullable=true)
     */
    protected $type; // pin or plastic

    /**
     * @var string
     *
     * @ORM\Column(name="NOTE", type="string", length=512 , nullable=true)
     */
    protected $note;

    /**
     * @ORM\Column(name="PICTURE", type="string", length=255, nullable=true)
     */
    protected $picture;

    /**
     * @Assert\File(maxSize="5M")
     */
    public $pictureFile;

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

    /**
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param string $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getLogo()
    {
        return $this->picture === null ? 'assets/bank.png' : $this->getWebLogoPath();
    }

    /**
     * @param mixed $picture
     *
     * @return Product
     */
    public function setLogo($picture)
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getLogoFile()
    {
        return $this->pictureFile;
    }

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setLogoFile(UploadedFile $file = null)
    {
        $this->pictureFile = $file;
        if (isset($this->picture)) {
            $this->tempLogo = $this->picture;
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
            $this->picture = $this->getRandomFileName() . '.' . $this->getLogoFile()->guessExtension();
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
                $this->picture
            );
            if (isset($this->tempLogo)) {
                @unlink($this->getUploadRootDir() . '/' . $this->tempLogo);
                $this->tempLogo = null;
            }
            $this->pictureFile = null;
        }
    }

    public function getUploadRootDir()
    {
        return __DIR__ . '/../../../../web' . $this->getUploadDir();
    }

    public function getUploadDir()
    {
        return 'uploads/products';
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
        return null === $this->picture
            ? null
            : $this->getUploadRootDir() . '/' . $this->picture;
    }

    public function getWebLogoPath()
    {
        return null === $this->picture
            ? null
            : strpos($this->picture, 'http') === 0 ? $this->picture : $this->getUploadDir() . '/' . $this->picture;
    }
}