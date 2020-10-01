<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Api\Data;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

interface ProductionTicketRepositoryInterface
{
    /**
     * Create or update a production ticket.
     *
     * @param ProductionTicketInterface $productionTicket
     * @return ProductionTicketInterface
     * @throws LocalizedException
     */
    public function save(ProductionTicketInterface $productionTicket);

    /**
     * Retrieve production ticket.
     *
     * @param string $orderItemLabel
     * @return  ProductionTicketInterface
     * @throws NoSuchEntityException If production ticket with the specified order_item_label does not exist.
     * @throws LocalizedException
     */
    public function get(string $orderItemLabel);

    /**
     * Get production ticket by ticket Entity ID.
     *
     * @param int $entityId
     * @return ProductionTicketInterface
     * @throws NoSuchEntityException If production ticket with the specified Entity ID does not exist.
     * @throws LocalizedException
     */
    public function getById(int $entityId);

    /**
     * Retrieve production tickets which match a specified criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return ProductionTicketSearchResultsInterface
     * @throws LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete production ticket.
     *
     * @param ProductionTicketInterface $productionTicket
     * @return bool true on success
     * @throws LocalizedException
     */
    public function delete(ProductionTicketInterface $productionTicket);

    /**
     * Delete production ticket by Entity ID.
     *
     * @param int $entityId
     * @return bool true on success
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function deleteById(int $entityId);
}
