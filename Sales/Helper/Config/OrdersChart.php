<?php

declare(strict_types=1);

namespace Labelin\Sales\Helper\Config;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class OrdersChart extends AbstractHelper
{
    protected const XML_PATH_CHART_WIDTH  = 'labelin_sales/orders_chart_configuration/width';
    protected const XML_PATH_CHART_HEIGHT = 'labelin_sales/orders_chart_configuration/height';

    public function getChartWidth($storeId = null): int
    {
        return (int)$this->scopeConfig->getValue(
            static::XML_PATH_CHART_WIDTH,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function getChartHeight($storeId = null): int
    {
        return (int)$this->scopeConfig->getValue(
            static::XML_PATH_CHART_HEIGHT,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
}
