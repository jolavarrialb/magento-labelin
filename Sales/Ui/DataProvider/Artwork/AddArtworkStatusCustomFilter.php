<?php

declare(strict_types=1);

namespace Labelin\Sales\Ui\DataProvider\Artwork;

use Labelin\Sales\Ui\DataProvider\AddCustomFilterInterface;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;

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

    public function addFilter(SearchCriteriaBuilder $searchCriteriaBuilder, $field, $value): void
    {
        $filter = $this->filterBuilder->setField($field)->setValue($value)->setConditionType(static::FILTER_COND_VALUE)->create();
        $searchCriteriaBuilder->addFilter($filter);
    }
}
