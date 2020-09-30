<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Block\Adminhtml\Order\View\Column;

use Labelin\ProductionTicket\Model\Order\Item;
use Magento\Framework\View\Element\Template;

class InProductionForm extends Template
{
    public function isAvailable(): bool
    {
        if (!$this->getItem()) {
            return false;
        }

        return $this->getItem()->isReadyForProduction();
    }

    public function getActionUrl(): string
    {
        if (!$this->getItem()) {
            return '';
        }

        return $this->getUrl('production_ticket/order_item/inProduction', ['item_id' => $this->getItem()->getId()]);
    }

    public function getItem(): ?Item
    {
        return $this->getParentBlock()->getData('item');
    }
}
