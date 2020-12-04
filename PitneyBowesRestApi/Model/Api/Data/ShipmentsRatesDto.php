<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesRestApi\Model\Api\Data;

use Labelin\PitneyBowesRestApi\Api\Data\ShipmentsRatesDtoInterface;

class ShipmentsRatesDto implements ShipmentsRatesDtoInterface
{
    protected $carrier = '';

    protected $serviceId = '';

    protected $parcelType = '';

    public function setCarrier(string $carrier): self
    {
        $this->carrier = $carrier;

        return $this;
    }

    public function getCarrier(): string
    {
        return $this->carrier;
    }

    public function setServiceId(string $serviceId): self
    {
        $this->serviceId = $serviceId;

        return $this;
    }

    public function getServiceId(): string
    {
        return $this->serviceId;
    }

    public function setParcelType(string $parcelType): self
    {
        $this->parcelType = $parcelType;

        return $this;
    }

    public function getParcelType(): string
    {
        return $this->parcelType;
    }
}
