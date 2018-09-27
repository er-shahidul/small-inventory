<?php

namespace Bundle\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_profiles")
 * @ORM\HasLifecycleCallbacks
 */
class Profile
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="Bundle\UserBundle\Entity\User", inversedBy="profile")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id", unique=true, onDelete="CASCADE")
     * })
     */
    protected $user;

    /**
     * @var string
     *
     * @ORM\Column(name="full_name_en", type="string", length=255 , nullable=true)
     * @Assert\NotBlank()
     */
    protected $fullNameEn;

    /**
     * @var string
     *
     * @ORM\Column(name="cellphone", type="string", length=100, nullable=true)
     * @Assert\NotBlank()
     */
    protected $cellphone;

    /**
     * @var array $type
     * Values (
    'Male', 'Female', 'Other'
     * )
     * @ORM\Column(name="gender", type="string", length=255, nullable=true)
     */
    protected $gender;

    /**
     * @var string
     *
     * @ORM\Column(name="designation", type="string", length=255, nullable=true)
     */
    protected $designation;

    /**
     * @var string
     *
     * @ORM\Column(name="dob", type="date", length=255, nullable=true)
     */
    protected $dob;

    /**
     * @var string
     *
     * @ORM\Column(name="blood_group", type="string", length=255, nullable=true)
     */
    protected $bloodGroup;

    /**
     * @var string
     *
     * @ORM\Column(name="current_address", type="text", nullable=true)
     */
    protected $currentAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="permanent_address", type="text", nullable=true)
     */
    protected $permanentAddress;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $photo;

    /**
     * @Assert\File(maxSize="5M")
     */
    public $photoFile;

    public $tempPhoto;

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
     * Set user
     *
     * @param User $user
     * @return Profile
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set cellphone
     *
     * @param string $cellphone
     * @return Profile
     */
    public function setCellphone($cellphone)
    {
        $this->cellphone = $cellphone;

        return $this;
    }

    /**
     * Get cellphone
     *
     * @return string
     */
    public function getCellphone()
    {
        return $this->cellphone;
    }

    /**
     * Set designation
     *
     * @param string $designation
     * @return Profile
     */
    public function setDesignation($designation)
    {
        $this->designation = $designation;

        return $this;
    }

    /**
     * Get designation
     *
     * @return string
     */
    public function getDesignation()
    {
        return $this->designation;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getPhotoFile()
    {
        return $this->photoFile;
    }

    /**
     * @return string
     */
    public function getFullNameEn()
    {
        return $this->fullNameEn;
    }

    /**
     * @param string $fullNameEn
     *
     * @return Profile
     */
    public function setFullNameEn($fullNameEn)
    {
        $this->fullNameEn = $fullNameEn;

        return $this;
    }

    /**
     * @return array
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param array $gender
     *
     * @return Profile
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * @return string
     */
    public function getDob()
    {
        return $this->dob;
    }

    /**
     * @param string $dob
     *
     * @return Profile
     */
    public function setDob($dob)
    {
        $this->dob = $dob;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhoto()
    {
        return $this->photo === null ? 'assets/global/img/avatar.png' : $this->getWebPhotoPath();
    }

    /**
     * @param mixed $photo
     *
     * @return Profile
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setPhotoFile(UploadedFile $file = null)
    {
        $this->photoFile = $file;
        // check if we have an old image path
        if (isset($this->photo)) {
            // store the old name to delete after the update
            $this->tempPhoto = $this->photo;
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
        if (null !== $this->getPhotoFile()) {
            // do whatever you want to generate a unique name
            $this->photo = $this->getRandomFileName() . '.' . $this->getPhotoFile()->guessExtension();
        }

    }


    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null !== $this->getPhotoFile()) {
            $this->getPhotoFile()->move(
                $this->getUploadRootDir(),
                $this->photo
            );
            // check if we have an old image
            if (isset($this->tempPhoto)) {
                // delete the old image
                @unlink($this->getUploadRootDir() . '/' . $this->tempPhoto);
                // clear the temp image path
                $this->tempPhoto = null;
            }
            $this->photoFile = null;
        }
    }

    public function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }

    public function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/users';
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

    public function removePhotoFile($file)
    {
        $file_path = $this->getUploadRootDir().'/'.$file;

        if(file_exists($file_path)) unlink($file_path);
    }

    public function getAbsolutePath()
    {
        return null === $this->photo
            ? null
            : $this->getUploadRootDir() . '/' . $this->photo;
    }

    public function getWebPhotoPath()
    {
        return null === $this->photo
            ? null
            : strpos($this->photo, 'http') === 0 ? $this->photo : $this->getUploadDir() . '/' . $this->photo;
    }

    /**
     * @return mixed
     */
    public function getBloodGroup()
    {
        return $this->bloodGroup;
    }

    /**
     * @param mixed $bloodGroup
     *
     * @return Profile
     */
    public function setBloodGroup($bloodGroup)
    {
        $this->bloodGroup = $bloodGroup;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCurrentAddress()
    {
        return $this->currentAddress;
    }

    /**
     * @param mixed $currentAddress
     *
     * @return Profile
     */
    public function setCurrentAddress($currentAddress)
    {
        $this->currentAddress = $currentAddress;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPermanentAddress()
    {
        return $this->permanentAddress;
    }

    /**
     * @param mixed $permanentAddress
     *
     * @return Profile
     */
    public function setPermanentAddress($permanentAddress)
    {
        $this->permanentAddress = $permanentAddress;

        return $this;
    }
}