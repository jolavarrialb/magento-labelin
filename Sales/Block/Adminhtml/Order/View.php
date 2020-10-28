<?php

declare(strict_types=1);

namespace Labelin\Sales\Block\Adminhtml\Order;

use Labelin\Sales\Model\Order;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Sales\Block\Adminhtml\Order\View as MagentoView;

class View extends MagentoView
{
    protected function _construct()
    {
        parent::_construct();

        if (!$this->isAvailableShipButton()) {
            $this->removeButton('order_ship');
        }

        if (!$this->isAvailableInvoiceButton()) {
            $this->removeButton('order_invoice');
        }

        if (!$this->isAvailableCancelButton()) {
            $this->removeButton('order_cancel');
        }
    }

    protected function isAvailableShipButton(): bool
    {
        $canShip = true;
        foreach ($this->getOrder()->getAllItems() as $item) {
            if ($item->getProductType() === Configurable::TYPE_CODE) {
                $canShip = false;
            }
        }

        if ($canShip) {
            return true;
        }

        return $this->getOrder()->canShip() && !$this->getOrder()->getForcedShipmentWithInvoice() &&
            $this->getOrder()->getAccessor()->isAllowedShipment() &&
            $this->getOrder()->getStatus() === Order::STATUS_READY_TO_SHIP;
    }

    protected function isAvailableInvoiceButton(): bool
    {
        return $this->getOrder()->canInvoice() && $this->getOrder()->getAccessor()->isAllowedInvoicing();
    }

    protected function isAvailableCancelButton(): bool
    {
        return $this->getOrder()->canCancel() && $this->getOrder()->getAccessor()->isAllowedCancellation();
    }
}
