<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesRestApi\Api;

interface CancelShipmentInterface
{
    /**
     * @param string $pitneyBowesShipmentId
     * @param int $magentoShipmentId
     *
     * @return \Labelin\PitneyBowesRestApi\Api\Data\CancelShipmentDtoInterface|null
     */
    public function cancelShipment(string $pitneyBowesShipmentId, int $magentoShipmentId);
}
