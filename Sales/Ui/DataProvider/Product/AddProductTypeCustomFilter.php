<?php

declare(strict_types=1);

namespace Labelin\Sales\Ui\DataProvider\Product;

use Labelin\Sales\Ui\DataProvider\LikeFilter;
use Magento\Framework\Api\Filter;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;

class AddProductTypeCustomFilter extends LikeFilter
{
    protected const REORDERED = 'is_reordered';

    protected const IS_REORDERED = 1;

    protected const COND_IN = 'in';

    public function addFilter(SearchCriteriaBuilder $searchCriteriaBuilder, Filter $filter): void
    {
        if ($filter->getValue() === static::REORDERED) {
            $filter->setValue(static::IS_REORDERED);
            $filter->setField(static::REORDERED);
            $filter->setConditionType(static::FILTER_COND_VALUE);

            $searchCriteriaBuilder->addFilter($filter);
        } else {
            $searchCriteriaBuilder->addFilter($filter->setConditionType(static::FILTER_COND_VALUE));
        }
    }
}
