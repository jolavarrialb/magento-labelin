<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesRestApi\Model\Api\Data;

use Labelin\PitneyBowesRestApi\Api\Data\ParcelDtoInterface;

class ParcelDto implements ParcelDtoInterface
{
    /** @var int|float */
    protected $weight;

    /** @var int|float */
    protected $length;

    /** @var int|float */
    protected $width;

    /** @var int|float */
    protected $height;

    /** @var string */
    protected $weightUnitOfMeasurement;

    /** @var string */
    protected $dimensionsUnitOfMeasurement;

    /**
     * @param float|int $weight
     *
     * @return $this
     */
    public function setWeight($weight = 0.1): self
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * @return float|int
     */
    public function getWeight()
    {
        switch ($this->weightUnitOfMeasurement) {
            case self::WEIGHT_UNIT_OF_MEASURE_LBS:
                $this->weight *= self::OUNCES_POUND;
                break;
            case self::WEIGHT_UNIT_OF_MEASURE_KG:
                $this->weight *= self::OUNCES_KG;
                break;
            default:
                $this->weight;
                break;
        }

        return $this->weight;
    }

    /**
     * @param string $unitOfMeasurement
     *
     * @return $this
     */
    public function setWeightUnitOfMeasurement(string $unitOfMeasurement = self::DEFAULT_WEIGHT_UNIT_OF_MEASURE): self
    {
        $this->weightUnitOfMeasurement = $unitOfMeasurement;

        return $this;
    }

    public function getWeightUnitOfMeasurement(): string
    {
        return self::DEFAULT_WEIGHT_UNIT_OF_MEASURE;
    }

    /**
     * @param float|int $length
     *
     * @return $this
     */
    public function setLength($length = 0.1): self
    {
        $this->length = $length;

        return $this;
    }

    /**
     * @return float|int
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @param float|int $width
     *
     * @return $this
     */
    public function setWidth($width = 0.1): self
    {
        $this->width = $width;

        return $this;
    }

    /**
     * @return float|int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param float|int $height
     *
     * @return $this
     */
    public function setHeight($height = 0.1): self
    {
        $this->height = $height;

        return $this;
    }

    /**
     * @return float|int
     */
    public function getHeight()
    {
        return $this->height;
    }

    public function setDimensionsUnitOfMeasurement(
        string $unitOfMeasurement = self::DEFAULT_DIMENSION_UNIT_OF_MEASURE
    ): self {
        $this->dimensionsUnitOfMeasurement = $unitOfMeasurement;

        return $this;
    }

    public function getDimensionsUnitOfMeasurement(): string
    {
        return $this->dimensionsUnitOfMeasurement;
    }

    public function toDimensionsArray(): array
    {
        return [
            'length' => $this->getLength(),
            'height' => $this->getHeight(),
            'width' => $this->getWidth(),
            'unit_of_measurement' => $this->getDimensionsUnitOfMeasurement(),
            'irregular_parcel_girth' => $this->getIrregularParcelGirth(),
        ];
    }

    public function toWeightArray(): array
    {
        return [
            'weight' => $this->getWeight(),
            'unit_of_measurement' => $this->getWeightUnitOfMeasurement(),
        ];
    }

    /**
     * @return float|int
     */
    protected function getIrregularParcelGirth()
    {
        return 2 * ($this->getHeight() + $this->getWidth());
    }
}
