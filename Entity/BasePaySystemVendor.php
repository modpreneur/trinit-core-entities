<?php

namespace Trinity\Component\EntityCore\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * PaySystem.
 */
class BasePaySystemVendor
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
     * @var BasePaySystem paySystem
     *
     * @ORM\ManyToOne(targetEntity="PaySystem", inversedBy="vendors")
     * @ORM\JoinColumn(name="paySystem", referencedColumnName="id", onDelete="CASCADE")
     *
     * @Assert\NotBlank()
     * @Assert\NotNull()
     */
    protected $paySystem;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=32)
     *
     * @Assert\NotBlank()
     */
    protected $name;

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
     * @return BasePaySystem
     */
    public function getPaySystem()
    {
        return $this->paySystem;
    }

    /**
     * @param BasePaySystem $paySystem
     */
    public function setPaySystem($paySystem)
    {
        $this->paySystem = $paySystem;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return BasePaySystemVendor
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
     * @return string
     */
    public function __toString()
    {
        return $this->paySystem && $this->paySystem->getName() ?
            $this->paySystem->getName() . ':' . $this->name : $this->getName();
    }
}
