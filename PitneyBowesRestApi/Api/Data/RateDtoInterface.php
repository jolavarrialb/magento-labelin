<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesRestApi\Api\Data;

interface RateDtoInterface
{
    /**
     * @param string $serviceId
     *
     * @return $this
     */
    public function setServiceId(string $serviceId);

    /**
     * @return string
     */
    public function getServiceId(): string;

    /**
     * @param string $service
     *
     * @return $this
     */
    public function setService(string $service);

    /**
     * @return string
     */
    public function getService(): string;

    /**
     * @param float $charge
     *
     * @return $this
     */
    public function setBaseCharge($charge);

    /**
     * @return float
     */
    public function getBaseCharge();

    /**
     * @param string $typeId
     *
     * @return $this
     */
    public function setRateTypeId(string $typeId);

    /**
     * @return string|null
     */
    public function getRateTypeId(): ?string;

    /**
     * @param float $charge
     *
     * @return $this
     */
    public function setTotalCarrierCharge($charge);

    /**
     * @return float
     */
    public function getTotalCarrierCharge();
}
