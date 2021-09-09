<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Block\Adminhtml\Order\View\Column;

use Labelin\ProductionTicket\Model\Order\Item;
use Magento\Framework\View\Element\Template;

class InProductionForm extends Template
{
    protected const URL_ROUTE_IN_PRODUCTION = 'production_ticket/order_item/inProduction';

    public function isAvailable(): bool
    {
        if (!$this->getItem()) {
            return false;
        }

        return $this->getItem()->isReadyForProduction() && !$this->getItem()->getIsReordered();
    }

    public function getActionUrl(): string
    {
        if (!$this->getItem()) {
            return '';
        }

        return $this->getUrl(static::URL_ROUTE_IN_PRODUCTION, ['item_id' => $this->getItem()->getId()]);
    }

    public function getItem(): ?Item
    {
        return $this->getParentBlock()->getData('item');
    }
}
