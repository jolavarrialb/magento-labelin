<?php

declare(strict_types=1);

namespace Labelin\Sales\Ui;

use Magento\Framework\Api\Filter;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\ReportingInterface;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider as MagentoDataProvider;

class DataProvider extends MagentoDataProvider
{
    /** @var array */
    protected $addFilterStrategies;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        ReportingInterface $reporting,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        RequestInterface $request,
        FilterBuilder $filterBuilder,
        array $meta = [],
        array $addFilterStrategies = [],
        array $data = []
    ) {
        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $reporting,
            $searchCriteriaBuilder,
            $request,
            $filterBuilder,
            $meta,
            $data
        );

        $this->addFilterStrategies = $addFilterStrategies;
    }

    /**
     * @inheritdoc
     */
    public function addFilter(Filter $filter)
    {
        if (!empty($this->addFilterStrategies[$filter->getField()])) {
            $this->addFilterStrategies[$filter->getField()]->addFilter($this->searchCriteriaBuilder, $filter);
        } else {
            parent::addFilter($filter);
        }
    }
}
