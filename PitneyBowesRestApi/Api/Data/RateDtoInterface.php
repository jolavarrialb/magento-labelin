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
     * @param int|float $charge
     *
     * @return $this
     */
    public function setBaseCharge($charge);

    /**
     * @return int|float
     */
    public function getBaseCharge();

    /**
     * @param string $typeId
     *
     * @return $this
     */
    public function setRateTypeId(string $typeId);

    /**
     * @return string
     */
    public function getRateTypeId(): string;

    /**
     * @param int|float $charge
     *
     * @return $this
     */
    public function setTotalCarrierCharge($charge);

    /**
     * @return int|float
     */
    public function getTotalCarrierCharge();
}
