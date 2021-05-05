<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesRestApi\Api\Data;

interface ParcelDtoInterface
{
    public const DEFAULT_WEIGHT_UNIT_OF_MEASURE = 'OZ';
    public const WEIGHT_UNIT_OF_MEASURE_LBS = 'LB';
    public const WEIGHT_UNIT_OF_MEASURE_KG = 'KG';

    public const DEFAULT_DIMENSION_UNIT_OF_MEASURE = 'IN';

    public const OUNCES_POUND = 16;
    public const OUNCES_KG = 35.274;

    /**
     * @param float $weight
     *
     * @return $this
     */
    public function setWeight(float $weight = 0.1);

    /**
     * @return float
     */
    public function getWeight();

    /**
     * @param string $unitOfMeasurement
     *
     * @return $this
     */
    public function setWeightUnitOfMeasurement(string $unitOfMeasurement = self::DEFAULT_WEIGHT_UNIT_OF_MEASURE);

    /**
     * @return string
     */
    public function getWeightUnitOfMeasurement(): string;

    /**
     * @param float $length
     *
     * @return $this
     */
    public function setLength(float $length = 0.1);

    /**
     * @return float
     */
    public function getLength();

    /**
     * @param float $width
     *
     * @return $this
     */
    public function setWidth(float $width = 0.1);

    /**
     * @return float
     */
    public function getWidth();

    /**
     * @param float $height
     *
     * @return $this
     */
    public function setHeight(float $height = 0.1);

    /**
     * @return float
     */
    public function getHeight();

    /**
     * @param string $unitOfMeasurement
     *
     * @return $this
     */
    public function setDimensionsUnitOfMeasurement(string $unitOfMeasurement = self::DEFAULT_DIMENSION_UNIT_OF_MEASURE);

    /**
     * @return string
     */
    public function getDimensionsUnitOfMeasurement(): string;

    /**
     * @return array
     */
    public function toDimensionsArray(): array;

    /**
     * @return array
     */
    public function toWeightArray(): array;

    /**
     * @return string
     */
    public function getPackageContainer(): string;

    /**
     * @param string $packageContainer
     *
     * @return $this
     */
    public function setPackageContainer(string $packageContainer = ''): self;
}
