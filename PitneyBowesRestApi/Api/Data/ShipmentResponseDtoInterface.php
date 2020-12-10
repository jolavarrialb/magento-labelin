<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesRestApi\Api\Data;

interface ShipmentResponseDtoInterface
{
    /**
     * @param string $content
     *
     * @return $this
     */
    public function setShippingLabelContent(string $content);

    /**
     * @return string
     */
    public function getShippingLabelContent(): string;

    /**
     * @param string $trackNumber
     *
     * @return $this
     */
    public function setTrackingNumber(string $trackNumber);

    /**
     * @return string
     */
    public function getTrackingNumber(): string;

    /**
     * @param string $error
     *
     * @return $this
     */
    public function setErrors(string $error = '');

    /**
     * @return string
     */
    public function getErrors(): string;

    /**
     * @return bool
     */
    public function hasErrors(): bool;
}
