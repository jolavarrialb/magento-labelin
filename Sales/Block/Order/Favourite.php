<?php

declare(strict_types=1);

namespace Labelin\Sales\Block\Order;

use Magento\Sales\Block\Order\History;

class Favourite extends History
{
    /** @var string  */
    protected $_template = "Labelin_Sales::order/history.phtml";

    public function getOrders()
    {
        $orders = parent::getOrders();

        if (!$orders) {
            return $orders;
        }

        $this->orders = $orders->addFieldToFilter('is_favourite', true);

        return $this->orders;
    }
}
