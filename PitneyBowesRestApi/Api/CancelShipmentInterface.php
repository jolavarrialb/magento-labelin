<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesRestApi\Api;

interface CancelShipmentInterface
{
    /**
     * @param int $shipmentId
     *
     * @return \Labelin\PitneyBowesRestApi\Api\Data\CancelShipmentDtoInterface|null
     */
    public function cancelShipment(int $shipmentId);
}
