<?php

namespace Trinity\Component\EntityCore\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Trinity\Component\Core\Interfaces\EntityInterface;

/**
 * Class BaseUser.
 *
 * @ORM\MappedSuperclass
 *
 * @UniqueEntity(fields={"username"})
 * @UniqueEntity(fields={"email"})
 */
class BaseUser extends User implements EntityInterface
{
    use Timestampable;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     *
     * @Assert\Length(
     *     min = 2,
     *     max = 50,
     *     minMessage = "Username must be at least {{ limit }} characters long",
     *     maxMessage = "Username cannot be longer than {{ limit }} characters"
     * )
     */
    protected $username;

    /**
     * @var string
     * @ORM\Column(type="string", length=50, nullable=true)
     *
     * @Assert\Length(
     *     min = 2,
     *     max = 50,
     *     minMessage = "First name must be at least {{ limit }} characters long",
     *     maxMessage = "First name cannot be longer than {{ limit }} characters"
     * )
     */
    protected $firstName;

    /**
     * @var string
     * @ORM\Column(type="string", length=50, nullable=true)
     *
     * @Assert\Length(
     *     min = 2,
     *     max = 50,
     *     minMessage = "Last name must be at least {{ limit }} characters long",
     *     maxMessage = "Last name cannot be longer than {{ limit }} characters"
     * )
     */
    protected $lastName;

    /**
     * @var string
     * @ORM\Column(type="string", length=22, nullable=true)
     *
     * @Assert\Length(
     *      max = 22,
     *      maxMessage = "Phone number cannot be longer than {{ limit }} characters"
     * )
     */
    protected $phoneNumber;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "Website cannot be longer than {{ limit }} characters"
     * )
     */
    protected $website;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "Avatar path cannot be longer than {{ limit }} characters"
     * )
     */
    protected $avatar;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=true))
     */
    protected $public = true;


    /**
     * @Assert\Regex(
     *     pattern="/.{8}/",
     *     message="Password must contain at least 8 characters."
     * )
     * @Assert\Regex(
     *     pattern="/[a-z]/",
     *     message="Password must contain at least 1 lowercase."
     * )
     * @Assert\Regex(
     *     pattern="/[A-Z]/",
     *     message="Password must contain at least 1 uppercase."
     * )
     * @Assert\Regex(
     *     pattern="/\d/",
     *     message="Password must contain at least 1 number."
     * )
     */
    protected $plainPassword;


    /**
     * BaseUser constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->roles = ['ROLE_USER'];
        $this->enabled = true;
    }


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Get firstName.
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set firstName.
     *
     * @param string $firstName
     *
     * @return $this
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get lastName.
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set lastName.
     *
     * @param string $lastName
     *
     * @return $this
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get fullName.
     *
     * @return string
     */
    public function getFullName()
    {
        if ($this->firstName && $this->lastName) {
            return "{$this->firstName} {$this->lastName}";
        } elseif ($this->firstName) {
            return $this->firstName;
        } elseif ($this->lastName) {
            return $this->lastName;
        } else {
            return '';
        }
    }

    /**
     * Get phoneNumber.
     *
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * Set phoneNumber.
     *
     * @param string $phoneNumber
     *
     * @return $this
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Get website.
     *
     * @return string
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Set website.
     *
     * @param string $website
     *
     * @return $this
     */
    public function setWebsite($website)
    {
        $this->website = $website;

        return $this;
    }

    /**
     * Get avatar.
     *
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Set avatar.
     *
     * @param string $avatar
     *
     * @return $this
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get public.
     *
     * @return bool
     */
    public function isPublic()
    {
        return $this->public;
    }

    /**
     * Set public.
     *
     * @param bool $public
     *
     * @return User
     */
    public function setPublic($public)
    {
        $this->public = $public;

        return $this;
    }

    /**
     * Is Admin.
     *
     * @return bool
     */
    public function isAdmin()
    {
        return ($this->hasRole('ROLE_ADMIN') || $this->hasRole('ROLE_SUPER_ADMIN'));
    }

    /**
     * Is SuperAdmin.
     *
     * @return bool
     */
    public function isSuperAdmin()
    {
        return $this->hasRole('ROLE_SUPER_ADMIN');
    }

    /**
     * Set Admin.
     *
     * @param bool $admin
     *
     * @return User
     */
    public function setAdmin($admin)
    {
        if ($admin) {
            $this->addRole('ROLE_ADMIN');
        } else {
            $this->removeRole('ROLE_ADMIN');
        }

        return $this;
    }


    /**
     * @return string
     */
    public function __toString() : string
    {
        return $this->username ?? '';
    }


    /**
     * @return int
     */
    public function getSettingIdentifier()
    {
        return $this->getId();
    }
}
