<?php

declare(strict_types=1);

namespace Labelin\Sales\Ui\DataProvider;

use Magento\Framework\Api\Search\SearchCriteriaBuilder;

interface AddCustomFilterInterface
{
    public function addFilter(SearchCriteriaBuilder $searchCriteriaBuilder, string $field, string $value): void;
}
