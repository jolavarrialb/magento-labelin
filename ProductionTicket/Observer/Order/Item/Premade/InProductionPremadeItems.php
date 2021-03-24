<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Observer\Order\Item\Premade;

use Labelin\ProductionTicket\Observer\Order\Item\InProductionStatus;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Model\Order\Item;

class InProductionPremadeItems extends InProductionStatus implements ObserverInterface
{

    /**
     * @param Observer $observer
     *
     * @return $this
     * @throws \Exception
     */
    public function execute(Observer $observer): self
    {
        $this->order = $observer->getData('order');

        if (!$this->order) {
            return $this;
        }

        /** @var Item $item */
        foreach ($this->order->getItems() as $orderItem) {
            if ($this->premadeHelper->isPremade($item)) {
                $artwork = $this->getArtwork($orderItem);

                $this->saveProductionTicket($orderItem, $artwork);
            }
        }

        return $this;
    }

    protected function getArtwork(Item $item): string
    {
        return 'value';
    }
}
