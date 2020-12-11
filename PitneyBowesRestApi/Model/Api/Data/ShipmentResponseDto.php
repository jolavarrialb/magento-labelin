<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesRestApi\Model\Api\Data;

use Labelin\PitneyBowesRestApi\Api\Data\ShipmentResponseDtoInterface;

class ShipmentResponseDto implements ShipmentResponseDtoInterface
{
    /** @var string */
    protected $labelContent;

    /** @var string */
    protected $trackNumber;

    /** @var string */
    protected $errors = '';

    public function setShippingLabelContent(string $content): self
    {
        $this->labelContent = $content;

        return $this;
    }

    public function getShippingLabelContent(): string
    {
        return $this->labelContent;
    }

    public function setTrackingNumber(string $trackNumber): self
    {
        $this->trackNumber = $trackNumber;

        return $this;
    }

    public function getTrackingNumber(): string
    {
        return $this->trackNumber;
    }

    public function setErrors(string $error = '')
    {
        $this->errors = $error;

        return $this;
    }

    public function getErrors(): string
    {
        return $this->errors;
    }

    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }
}
