<?php

declare(strict_types=1);

namespace Labelin\Sales\Helper\Config;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Overdue extends AbstractHelper
{
    protected const XML_PATH_LABELIN_SALES_OVERDUE_DAYS = 'labelin_sales/order_configuration/overdue_days';

    public function getOverdueDays($storeId = null): int
    {
        return (int)$this->scopeConfig->getValue(
            static::XML_PATH_LABELIN_SALES_OVERDUE_DAYS,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
}
