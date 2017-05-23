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
 * @ORM\MappedSuperclass()
 */
class BaseBillingPlan implements EntityInterface, BillingPlanInterface
{
    use ORMBehaviors\Timestampable\Timestampable,
        ORMBehaviors\Blameable\Blameable,
        ExcludeBlameableTrait;

    const FREQUENCY_BY_STRING = [
        'weekly' => 7,
        'bi_weekly' => 14,  //cb requires underscore!!
        'monthly' => 30,
        'quartaly' => 91,
    ];

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
     * @ORM\Column(type="boolean")
     */
    protected $isRecurring = false;


    /**
     * @var float
     *
     * @ORM\Column(type="decimal", precision=8, scale=2)
     *
     * @Assert\LessThanOrEqual(value=100000)
     * @Assert\NotBlank()
     * @Assert\NotNull()
     */
    protected $initialPrice;

    /**
     * @var float|null
     *
     * @ORM\Column(type="decimal", precision=7, scale=2, nullable=true)
     *
     * @Assert\LessThan(value=100000)
     */
    protected $rebillPrice;

    /**
     * @var int|null
     *
     * @ORM\Column(type="smallint", nullable=true)
     */
    protected $frequency;

    /**
     * @var int|null
     *
     * @ORM\Column(type="smallint", nullable=true)
     */
    protected $rebillTimes;

    /**
     * @var int|null
     *
     * @ORM\Column(type="smallint", nullable=true)
     */
    protected $trial;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(min = 2, max = 255)
     */
    protected $itemId;


    /**
     * Get id.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * @return int|null
     */
    public function getFrequency(): ?int
    {
        return $this->frequency;
    }


    /**
     * @param int $frequency
     */
    public function setFrequency(int $frequency): void
    {
        $this->frequency = $frequency;
    }


    /**
     * Due to new form it may return null
     * @return null|float
     */
    public function getInitialPrice(): ?float
    {
        return $this->initialPrice;
    }


    /**
     * @param float $initialPrice
     */
    public function setInitialPrice(float $initialPrice): void
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
    public function setProduct($product): void
    {
        $this->product = $product;
    }


    /**
     * @return float|null
     */
    public function getRebillPrice(): ?float
    {
        return $this->rebillPrice;
    }


    /**
     * @param float $rebillPrice
     */
    public function setRebillPrice(float $rebillPrice): void
    {
        $this->rebillPrice = $rebillPrice;
    }


    /**
     * @return int|null
     */
    public function getRebillTimes(): ?int
    {
        return $this->rebillTimes;
    }


    /**
     * @param int $rebillTimes
     */
    public function setRebillTimes(int $rebillTimes): void
    {
        $this->rebillTimes = $rebillTimes;
    }


    /**
     * @return int|null
     */
    public function getTrial(): ?int
    {
        return $this->trial;
    }


    /**
     * @param int $trial
     */
    public function setTrial(int $trial): void
    {
        $this->trial = $trial;
    }

    /**
     * @return string|null
     */
    public function getItemId(): ?string
    {
        return $this->itemId;
    }

    /**
     * @param string $itemId
     */
    public function setItemId(string $itemId): void
    {
        //CB store case sensitive itemID, but sends in IPN lower
        $this->itemId = strtolower($itemId);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        if ($this->frequency > 0) {
            return 'recurring';
        }
        return 'standard';
    }


    /**
     * @return bool
     */
    public function isRecurring(): bool
    {
        return $this->isRecurring || $this->frequency > 0;
    }


    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        if (strtolower($type) === 'standard') {
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
        }

        return $this->getType() . ' ' . $this->initialPrice;
    }
}
