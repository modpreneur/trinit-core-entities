<?php

namespace Trinity\Component\EntityCore\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Trinity\Component\Core\Interfaces\EntityInterface;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Trinity\Component\Core\Interfaces\EntityInterface;

/**
 * PaySystem.
 */
class BasePaySystem implements EntityInterface
{
    use ORMBehaviors\Timestampable\Timestampable;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=32)
     *
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     *
     * @ORM\OneToMany(targetEntity="PaySystemVendor",  mappedBy="paySystem")
     * @ORM\OrderBy({"id" = "ASC"})
     */
    protected $vendors;

    /**
     * @var BasePaySystemVendor default vendor for given pay system
     *
     * @ORM\OneToOne(targetEntity="PaySystemVendor")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="SET NULL")
     */
    protected $defaultVendor;

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
     * Set name.
     *
     * @param string $name
     *
     * @return BasePaySystem
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return PersistentCollection
     */
    public function getVendors()
    {
        return $this->vendors;
    }

    /**
     * @return PersistentCollection
     */
    public function getVendorItems()
    {
        return $this->vendors;
    }

    /**
     * @return basePaySystemVendor
     */
    public function getDefaultVendor()
    {
        return $this->defaultVendor;
    }

    /**
     * @param BasePaySystemVendor $defaultVendor
     */
    public function setDefaultVendor($defaultVendor)
    {
        $this->defaultVendor = $defaultVendor;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }
}
