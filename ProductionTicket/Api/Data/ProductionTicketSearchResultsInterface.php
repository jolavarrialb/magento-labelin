<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface ProductionTicketSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get production ticket list.
     *
     * @return ProductionTicketInterface[]
     */
    public function getItems();

    /**
     * Set production ticket list.
     *
     * @param ProductionTicketInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
