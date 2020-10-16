<?php

declare(strict_types=1);

namespace Labelin\Sales\Helper\Config;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Sales\Api\Data\OrderItemInterface;
use Magento\Store\Model\ScopeInterface;

class ArtworkAwaitingCustomerApprove extends AbstractHelper
{
    public const DATE_TIME_FORMAT = 'Y-m-d H:s:i';

    protected const XML_PATH_ENABLED       = 'labelin_sales/artwork_awaiting_customer_approve_notification/enabled';
    protected const XML_PATH_EXCEEDED_DAYS = 'labelin_sales/artwork_awaiting_customer_approve_notification/exceeded_days';

    public function isEnabled($storeId = null): bool
    {
        return $this->scopeConfig->isSetFlag(static::XML_PATH_ENABLED, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function isAwaitingCustomerApproveExceeded(OrderItemInterface $orderItem): bool
    {
        if ($orderItem->getData('is_artwork_approved')
            && $orderItem->getData('is_designer_notified_on_awaiting_customer_approve')
        ) {
            return false;
        }

        $fromDate = new \DateTime();
        $fromDate->modify(sprintf('- %s days', $this->getExceededDays()));

        return $orderItem->getData('artwork_approval_by_designer_date') <= $fromDate->format(static::DATE_TIME_FORMAT);
    }

    public function getExceededDays($storeId = null): int
    {
        return (int)$this->scopeConfig->getValue(static::XML_PATH_EXCEEDED_DAYS, ScopeInterface::SCOPE_STORE, $storeId);
    }
}
