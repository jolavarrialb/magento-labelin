<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesRestApi\Model\Api\Data;

use Labelin\PitneyBowesRestApi\Api\Data\ParcelDtoInterface;

class ParcelDto implements ParcelDtoInterface
{
    /** @var float */
    protected $weight;

    /** @var float */
    protected $length;

    /** @var float */
    protected $width;

    /** @var float */
    protected $height;

    /** @var string */
    protected $weightUnitOfMeasurement;

    /** @var string */
    protected $dimensionsUnitOfMeasurement;

    public function setWeight(float $weight = 0.1): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getWeight(): float
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

    public function setWeightUnitOfMeasurement(string $unitOfMeasurement = self::DEFAULT_WEIGHT_UNIT_OF_MEASURE): self
    {
        $this->weightUnitOfMeasurement = $unitOfMeasurement;

        return $this;
    }

    public function getWeightUnitOfMeasurement(): string
    {
        return self::DEFAULT_WEIGHT_UNIT_OF_MEASURE;
    }

    public function setLength(float $length = 0.1): self
    {
        $this->length = $length;

        return $this;
    }

    public function getLength(): float
    {
        return $this->length;
    }

    public function setWidth(float $width = 0.1): self
    {
        $this->width = $width;

        return $this;
    }

    public function getWidth(): float
    {
        return $this->width;
    }

    public function setHeight(float $height = 0.1): self
    {
        $this->height = $height;

        return $this;
    }

    public function getHeight(): float
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

    protected function getIrregularParcelGirth(): float
    {
        return 2 * ($this->getHeight() + $this->getWidth());
    }
}
