<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Model\Order;

use Labelin\Sales\Model\Order\Item as SalesOrderItem;
use Magento\Framework\Exception\LocalizedException;

class Item extends SalesOrderItem
{
    public function isInProduction(): bool
    {
        return (bool)$this->getData('is_in_production');
    }

    public function isReadyForProduction(): bool
    {
        return $this->isArtworkApproved() && !$this->isInProduction();
    }

    /**
     * @return $this
     * @throws LocalizedException
     */
    public function markAsInProduction(): self
    {
        if (!$this->isReadyForProduction()) {
            throw new LocalizedException(__('A production action is not available.'));
        }

        $this->setData('is_in_production', true);

        return $this;
    }
}
