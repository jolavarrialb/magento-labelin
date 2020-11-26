<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesRestApi\Api\Data;

interface ParcelDtoInterface
{
    public const DEFAULT_WEIGHT_UNIT_OF_MEASURE = 'OZ';
    public const WEIGHT_UNIT_OF_MEASURE_LBS     = 'LB';
    public const WEIGHT_UNIT_OF_MEASURE_KG      = 'KG';

    public const DEFAULT_DIMENSION_UNIT_OF_MEASURE = 'IN';

    public const OUNCES_POUND = 16;
    public const OUNCES_KG = 35.274;
    /**
     * @param int|float $weight
     *
     * @return $this
     */
    public function setWeight($weight = 0);

    /**
     * @return int|float
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
     * @param int|float $length
     *
     * @return $this
     */
    public function setLength($length = 0.1);

    /**
     * @return int|float
     */
    public function getLength();

    /**
     * @param int|float $width
     *
     * @return $this
     */
    public function setWidth($width = 0.1);

    /**
     * @return int|float
     */
    public function getWidth();

    /**
     * @param int|float $height
     *
     * @return $this
     */
    public function setHeight($height = 0.1);

    /**
     * @return int|float
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
}
