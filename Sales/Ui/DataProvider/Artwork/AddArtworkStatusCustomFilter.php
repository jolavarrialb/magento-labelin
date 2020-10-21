<?php

declare(strict_types=1);

namespace Labelin\Sales\Ui\DataProvider\Artwork;

use Labelin\Sales\Ui\DataProvider\AddCustomFilterInterface;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\Api\Filter;

class AddArtworkStatusCustomFilter implements AddCustomFilterInterface
{
    protected const FILTER_COND_VALUE = 'like';

    /** @var FilterBuilder */
    protected $filterBuilder;

    public function __construct(
        FilterBuilder $filterBuilder
    ) {
        $this->filterBuilder = $filterBuilder;
    }

    public function addFilter(SearchCriteriaBuilder $searchCriteriaBuilder, Filter $filter): void
    {
        $searchCriteriaBuilder->addFilter($filter->setConditionType(static::FILTER_COND_VALUE));
    }
}
