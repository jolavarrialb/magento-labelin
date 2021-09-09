<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Block\Adminhtml\Order\View\Column;

class ReorderedInProductionForm extends InProductionForm
{
    protected const URL_ROUTE_IN_PRODUCTION = 'production_ticket/order_item/reorderInProduction';

    public function isAvailable(): bool
    {
        if (!$this->getItem()) {
            return false;
        }

        return $this->getItem()->isReadyForProduction() && $this->getItem()->getIsReordered();
    }
}
