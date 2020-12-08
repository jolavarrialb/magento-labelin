<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesRestApi\Model\Api\Data;

use Labelin\PitneyBowesRestApi\Api\Data\ShipmentsRatesDtoInterface;
use Labelin\PitneyBowesRestApi\Api\Data\SpecialServiceDtoInterface;

class ShipmentsRatesDto implements ShipmentsRatesDtoInterface
{
    protected $carrier = '';

    protected $serviceId = '';

    protected $parcelType = '';

    protected $inductionPostalCode = '';

    protected $specialService = [];

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

    public function setInductionPostalCode(string $postalCode): self
    {
        $this->inductionPostalCode = $postalCode;

        return $this;
    }

    public function getInductionPostalCode(): string
    {
        return $this->inductionPostalCode;
    }

    public function addSpecialService(SpecialServiceDtoInterface $specialServiceDto)
    {
        $this->specialService[] = $specialServiceDto;

        return $this;
    }

    public function getSpecialService(): array
    {
        return $this->specialService;
    }
}
