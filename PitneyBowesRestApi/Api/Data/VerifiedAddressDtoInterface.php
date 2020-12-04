<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesRestApi\Api\Data;

interface VerifiedAddressDtoInterface
{
    /**
     * @param string $deliveryPoint
     *
     * @return $this
     */
    public function setDeliveryPoint(string $deliveryPoint);

    /**
     * @return string
     */
    public function getDeliveryPoint(): string;

    /**
     * @param string $carrierRoute
     *
     * @return $this
     */
    public function setCarrierRoute(string $carrierRoute);

    /**
     * @return string
     */
    public function getCarrierRoute(): string;

    /**
     * @param string $status
     *
     * @return $this
     */
    public function setStatus(string $status);

    /**
     * @return string
     */
    public function getStatus(): string;

    /**
     * @return bool
     */
    public function isValid(): bool;
}
