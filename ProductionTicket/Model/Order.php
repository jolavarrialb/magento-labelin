<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Model;

use Labelin\Sales\Model\Order as LabelinSalesOrder;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;

class Order extends LabelinSalesOrder
{
    public function markAsProduction(): self
    {
        $this
            ->setStatus(static::STATUS_IN_PRODUCTION)
            ->addStatusToHistory(static::STATUS_IN_PRODUCTION, __('Order is on production'));

        return $this;
    }

    public function isAllItemsReadyForProduction(): bool
    {
        foreach ($this->getAllItems() as $item) {
            if ($item->getProductType() !== Configurable::TYPE_CODE || $item->isInProduction()) {
                continue;
            }

            if (!$item->isReadyForProduction()) {
                return false;
            }

        }

        return true;
    }

    public function isAllItemsInProduction(): bool
    {
        foreach ($this->getAllItems() as $item) {
            if ($item->getProductType() !== Configurable::TYPE_CODE) {
                continue;
            }

            if (!$item->isInProduction()) {
                return false;
            }
        }

        return true;
    }

}
