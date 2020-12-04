<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesRestApi\Api\Data;

interface CancelShipmentDtoInterface
{
    /**
     * @param string|null $initiator
     *
     * @return $this
     */
    public function setCancelInitiator(?string $initiator);

    /**
     * @return string
     */
    public function getCancelInitiator(): string;

    /**
     * @param string|null $carrier
     *
     * @return $this
     */
    public function setCarrier(?string $carrier);

    /**
     * @return string
     */
    public function getCarrier(): string;

    /**
     * @param string|null $trackingNumber
     *
     * @return $this
     */
    public function setParcelTrackingNumber(?string $trackingNumber);

    /**
     * @return string
     */
    public function getParcelTrackingNumber(): string;

    /**
     * @param string|null $status
     *
     * @return $this
     */
    public function setStatus(?string $status);

    /**
     * @return string
     */
    public function getStatus(): string;

    /**
     * @param string|null $charge
     *
     * @return $this
     */
    public function setTotalCarrierCharge(?string $charge);

    /**
     * @return string|null
     */
    public function getTotalCarrierCharge(): ?string;
}
