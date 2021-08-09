<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\DataProvider\Filters;

use Magento\Framework\Api\Filter;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;

class AddOrderedItemQtyFilterToGrid
{

    public function addFilter(SearchCriteriaBuilder $searchCriteriaBuilder, Filter $filter): void
    {
        $filter->setValue(str_replace(' ', '%', $filter->getValue()));

        $searchCriteriaBuilder->addFilter($filter);
    }
}
