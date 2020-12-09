<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesRestApi\Model\Api\Data;

use Labelin\PitneyBowesRestApi\Api\Data\ShipmentsRatesDtoInterface;

class ShipmentsRatesDto implements ShipmentsRatesDtoInterface
{
    /** @var string  */
    protected $carrier = '';

    /** @var string  */
    protected $serviceId = '';

    /** @var string  */
    protected $parcelType = '';

    /** @var string  */
    protected $inductionPostalCode = '';

    /** @var array  */
    protected $specialService = [];

    /**
     * @param string $carrier
     * @return $this
     */
    public function setCarrier(string $carrier): self
    {
        $this->carrier = $carrier;

        return $this;
    }

    /**
     * @return string
     */
    public function getCarrier(): string
    {
        return $this->carrier;
    }

    /**
     * @param string $serviceId
     * @return $this
     */
    public function setServiceId(string $serviceId): self
    {
        $this->serviceId = $serviceId;

        return $this;
    }

    /**
     * @return string
     */
    public function getServiceId(): string
    {
        return $this->serviceId;
    }

    /**
     * @param string $parcelType
     * @return $this
     */
    public function setParcelType(string $parcelType): self
    {
        $this->parcelType = $parcelType;

        return $this;
    }

    /**
     * @return string
     */
    public function getParcelType(): string
    {
        return $this->parcelType;
    }

    /**
     * @param string $postalCode
     * @return $this
     */
    public function setInductionPostalCode(string $postalCode): self
    {
        $this->inductionPostalCode = $postalCode;

        return $this;
    }

    /**
     * @return string
     */
    public function getInductionPostalCode(): string
    {
        return $this->inductionPostalCode;
    }
}
