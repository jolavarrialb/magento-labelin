<?php

declare(strict_types=1);

namespace Labelin\Sales\Ui;

use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\ReportingInterface;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider as MagentoDataProvider;

class DataProvider extends MagentoDataProvider
{
    protected const CUSTOM_FILTERS = [
        'artwork_status',
    ];

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

        $this->initCustomFilters();
    }

    protected function initCustomFilters(): void
    {
        $requestParams = $this->request->getParams();

        if (!array_key_exists('filters', $requestParams)) {
            return;
        };

        $filters = $requestParams['filters'];

        if (!is_array($filters) && !empty($filters)) {
            return;
        }

        foreach ($filters as $filterField => $value) {

            if (!in_array($filterField, static::CUSTOM_FILTERS)) {
                continue;
            }

            $this->addFilterStrategies[$filterField]->addFilter($this->searchCriteriaBuilder, $filterField, $value);
        }
    }
}
