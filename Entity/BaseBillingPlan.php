<?php

namespace Trinity\Component\EntityCore\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Component\Validator\Constraints as Assert;
use Trinity\Component\Core\Interfaces\BillingPlanInterface;
use Trinity\Component\Core\Interfaces\EntityInterface;
use Trinity\Component\Core\Interfaces\ProductInterface;
use Trinity\Component\EntityCore\Traits\ExcludeBlameableTrait;

/**
 * BillingPlan.
 *
 */
class BaseBillingPlan implements EntityInterface, BillingPlanInterface
{
    use ORMBehaviors\Timestampable\Timestampable,
        ORMBehaviors\Blameable\Blameable,
        ExcludeBlameableTrait;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var BaseProduct product
     *
     * @ORM\ManyToOne(targetEntity="BaseProduct")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     *
     * @Assert\NotBlank()
     */
    protected $product;


    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isRecurring;


    /**
     * @var float
     *
     * @ORM\Column(type="decimal", precision=7, scale=2)
     *
     * @Assert\LessThan(value=100000)
     * @Assert\NotBlank()
     */
    protected $initialPrice;

    /**
     * @var float
     *
     * @ORM\Column(type="decimal", precision=7, scale=2, nullable=true)
     *
     * @Assert\LessThan(value=100000)
     */
    protected $rebillPrice;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint", nullable=true)
     */
    protected $frequency;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint", nullable=true)
     */
    protected $rebillTimes;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint", nullable=true)
     */
    protected $trial;


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
     * @return int
     */
    public function getFrequency()
    {
        return $this->frequency;
    }


    /**
     * @param int $frequency
     */
    public function setFrequency($frequency)
    {
        $this->frequency = $frequency;
    }


    /**
     * @return float
     */
    public function getInitialPrice()
    {
        return $this->initialPrice;
    }


    /**
     * @param float $initialPrice
     */
    public function setInitialPrice($initialPrice)
    {
        $this->initialPrice = $initialPrice;
    }


    /**
     * @return ProductInterface
     */
    public function getProduct()
    {
        return $this->product;
    }


    /**
     * @param BaseProduct $product
     */
    public function setProduct($product)
    {
        $this->product = $product;
    }


    /**
     * @return float
     */
    public function getRebillPrice()
    {
        return $this->rebillPrice;
    }


    /**
     * @param float $rebillPrice
     */
    public function setRebillPrice($rebillPrice)
    {
        $this->rebillPrice = $rebillPrice;
    }


    /**
     * @return int
     */
    public function getRebillTimes()
    {
        return $this->rebillTimes;
    }


    /**
     * @param int $rebillTimes
     */
    public function setRebillTimes($rebillTimes)
    {
        $this->rebillTimes = $rebillTimes;
    }


    /**
     * @return int
     */
    public function getTrial()
    {
        return $this->trial;
    }


    /**
     * @param int $trial
     */
    public function setTrial($trial)
    {
        $this->trial = $trial;
    }


    /**
     * @return string
     */
    public function getType()
    {
        if ($this->frequency > 0) {
            return 'recurring';
        } else {
            return 'standard';
        }
    }


    /**
     * @return bool
     */
    public function isRecurring()
    {
        return $this->isRecurring||$this->frequency !== 0;
    }


    /**
     * @param string $type
     */
    public function setType($type)
    {
        if ($type === 'standard') {
            $this->frequency = null;
            $this->rebillPrice = null;
            $this->rebillTimes = null;
        }
        $this->isRecurring = false;
        if ($type === 'recurring') {
            $this->isRecurring = true;
        }
    }


    /**
     * @param bool $upperCase Return in uppercase and with underscores
     *
     * @return string
     */
    public function getFrequencyString(bool $upperCase = false) :string
    {
        switch ($this->frequency) {
            case 7:
                $str = 'weekly';
                break;
            case 14:
                $str = 'bi-weekly';
                break;
            case 30:
                $str = 'monthly';
                break;
            case 91:
                $str = 'quartaly';
                break;
            default:
                $str = '';
                break;
        }

        if ($upperCase) {
            $str = strtoupper($str);
            $str = str_replace('-', '_', $str);
        }

        return $str;
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        if ($this->isRecurring()) {
            $str = $this->getFrequencyString();
            return ($this->initialPrice + 0) . ' and ' .
                $this->rebillTimes . ' times ' . ($this->rebillPrice + 0) . ' ' . $str;
        } else {
            return $this->getType() . ' ' . $this->initialPrice;
        }
    }
}
