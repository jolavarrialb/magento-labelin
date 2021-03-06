<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesRestApi\Model\Api\Data;

use Labelin\PitneyBowesRestApi\Api\Data\RateDtoInterface;

class RateDto implements RateDtoInterface
{
    /** @var string */
    protected $serviceId = '';

    /** @var string */
    protected $service = '';

    /** @var float */
    protected $baseCharge;

    /** @var string */
    protected $typeId = '';

    /** @var float */
    protected $carrierCharge;

    public function setServiceId(string $serviceId): self
    {
        $this->serviceId = $serviceId;

        return $this;
    }

    public function getServiceId(): string
    {
        return $this->serviceId;
    }

    public function setService(string $service): self
    {
        $this->service = $service;

        return $this;
    }

    public function getService(): string
    {
        return (string)__($this->service);
    }

    /**
     * @param float $charge
     *
     * @return $this
     */
    public function setBaseCharge($charge)
    {
        $this->baseCharge = $charge;

        return $this;
    }

    /**
     * @return float
     */
    public function getBaseCharge()
    {
        return $this->baseCharge;
    }

    public function setRateTypeId(?string $typeId): self
    {
        $this->typeId = $typeId;

        return $this;
    }

    public function getRateTypeId(): ?string
    {
        return $this->typeId;
    }

    /**
     * @param float $charge
     *
     * @return $this
     */
    public function setTotalCarrierCharge($charge): self
    {
        $this->carrierCharge = $charge;

        return $this;
    }

    /**
     * @return float
     */
    public function getTotalCarrierCharge()
    {
        return $this->carrierCharge;
    }
}
