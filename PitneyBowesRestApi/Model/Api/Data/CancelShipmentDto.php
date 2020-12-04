<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesRestApi\Model\Api\Data;

use Labelin\PitneyBowesRestApi\Api\Data\CancelShipmentDtoInterface;

class CancelShipmentDto implements CancelShipmentDtoInterface
{
    /** @var string */
    protected $cancelInitiator = '';

    /** @var string */
    protected $carrier = '';

    /** @var string */
    protected $parcelTrackingNumber = '';

    /** @var string */
    protected $status = '';

    /** @var string */
    protected $totalCarrierCharge = '';

    public function setCancelInitiator(?string $initiator): self
    {
        $this->cancelInitiator = $initiator ?? '';

        return $this;
    }

    public function getCancelInitiator(): string
    {
        return $this->cancelInitiator;
    }

    public function setCarrier(?string $carrier): self
    {
        $this->carrier = $carrier ?? '';

        return $this;
    }

    public function getCarrier(): string
    {
        return $this->carrier;
    }

    public function setParcelTrackingNumber(?string $trackingNumber): self
    {
        $this->parcelTrackingNumber = $trackingNumber ?? '';

        return $this;
    }

    public function getParcelTrackingNumber(): string
    {
        return $this->parcelTrackingNumber;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status ?? '';

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setTotalCarrierCharge(?string $charge)
    {
        $this->totalCarrierCharge = $charge ?? '';

        return $this;
    }

    public function getTotalCarrierCharge(): ?string
    {
        return $this->totalCarrierCharge;
    }
}
