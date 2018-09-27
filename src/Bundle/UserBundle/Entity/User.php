<?php

namespace Bundle\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Traits\BlameableEntity;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="user_users")
 * @ORM\Entity(repositoryClass="Bundle\UserBundle\Repository\UserRepository")
 * @UniqueEntity(
 *     fields={"email"},
 *     message="This email is already in use."
 * )
 * @UniqueEntity(
 *     fields={"username"},
 *     message="This username is already in use."
 * )
 */
class User extends BaseUser
{
    use BlameableEntity;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToOne(targetEntity="Profile", mappedBy="user", cascade={"persist"})
     */
    protected $profile;

    /**
     * @ORM\ManyToMany(targetEntity="UserGroup", inversedBy="users")
     * @ORM\JoinTable(name="user_join_users_groups",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    protected $groups;

    public function __construct()
    {
        parent::__construct();
        $this->groups = new ArrayCollection();
    }

    /**
     * @return Profile
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * @param mixed $profile
     * @return $this
     */
    public function setProfile($profile)
    {
        $profile->setUser($this);

        $this->profile = $profile;

        return $this;
    }

    public function isSuperAdmin()
    {
        $groups = $this->getGroups();
        /** @var UserGroup $group */
        foreach ($groups as $group) {
            if ($group->hasRole('ROLE_SUPER_ADMIN')) {
                return true;
            }
        }

        return parent::isSuperAdmin();
    }

    /**
     * @return mixed
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * @param mixed $groups
     *
     * @return User
     */
    public function setGroups($groups)
    {
        $this->groups = is_array($groups) ? $groups : array($groups);

        return $this;
    }

    public function getNameAndDesig()
    {
        if (!$this->getProfile()) {
            $this->username;
        }

        return sprintf('%s %s %s %s %s', $this->username, PHP_EOL, $this->getProfile()->getFullNameBn(), PHP_EOL, $this->getProfile()->getDesignation());
    }
}