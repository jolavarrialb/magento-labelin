<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Api\Data;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

interface ProductionTicketRepositoryInterface
{
    public function save(ProductionTicketInterface $productionTicket): ProductionTicketInterface;

    public function get(int $entityId): ProductionTicketInterface;

    /**
     * @param SearchCriteriaInterface $searchCriteria
     *
     * @return ProductionTicketSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    public function delete(ProductionTicketInterface $productionTicket): bool;

    public function deleteById(int $entityId): bool;
}
