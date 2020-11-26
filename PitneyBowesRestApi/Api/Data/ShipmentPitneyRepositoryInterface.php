<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesRestApi\Api\Data;

use Magento\Framework\Api\SearchCriteriaInterface;

interface ShipmentPitneyRepositoryInterface
{
    public function save(ShipmentPitneyInterface $shipmentPitney): ShipmentPitneyInterface;

    public function get(int $entityId): ShipmentPitneyInterface;

    /**
     * @param SearchCriteriaInterface $searchCriteria
     *
     * @return ShipmentPitneySearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    public function delete(ShipmentPitneyInterface $shipmentPitney): bool;

    public function deleteById(int $entityId): bool;
}
