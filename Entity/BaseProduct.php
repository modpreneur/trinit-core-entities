<?php

namespace Trinity\Component\EntityCore\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Trinity\Component\Core\Interfaces\EntityInterface;
use Trinity\Component\Core\Interfaces\ProductInterface;

/**
 * Class BaseProduct.
 *
 * @UniqueEntity(fields="name")
 */
class BaseProduct implements EntityInterface, ProductInterface
{
    use ORMBehaviors\Timestampable\Timestampable;

    const TYPE_DIGITAL  = 'digital';
    const TYPE_PHYSICAL = 'physical';
    const TYPE_HYBRID   = 'hybrid';

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string Name of the product
     * @ORM\Column(type="string", unique=true)
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     * @var string Description of the product
     * @ORM\Column(type="string", nullable = true)
     */
    protected $description;

    /**
     * @var string
     * @ORM\Column(type="string", name="product_type", options={"default" : "digital"})
     */
    protected $productType;

    /**
     * BaseProduct constructor.
     */
    public function __construct()
    {
        $this->productType = self::TYPE_DIGITAL;
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
     * Set name.
     *
     * @param string $name
     *
     * @return BaseProduct
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
     * Set description.
     *
     * @param string $description
     *
     * @return BaseProduct
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }


    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }


    /**
     * @return string
     */
    public function __toString() : string
    {
        return (string)$this->getName();
    }

    /**
     * Return type of product (digital|physical).
     *
     * @return string
     */
    public function getProductType(): string
    {
        return $this->productType;
    }


    /**
     * @param string $type
     *
     * doc: http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/cookbook/mysql-enums.html
     *
     * @throws \InvalidArgumentException
     */
    public function setProductType(string $type)
    {
        $types = [self::TYPE_DIGITAL, self::TYPE_PHYSICAL, self::TYPE_HYBRID];

        if (!in_array($type, $types)) {
            throw new \InvalidArgumentException('Invalid productType \'' . $type . '\'');
        }
        $this->productType = $type;
    }
}
