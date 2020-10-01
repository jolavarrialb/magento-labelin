<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Observer\Order\Item;

use Labelin\ProductionTicket\Model\Order\Item;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Api\OrderItemRepositoryInterface;

class InProductionStatusHandler implements ObserverInterface
{
    /** @var OrderItemRepositoryInterface */
    protected $orderItemRepository;

    public function __construct(OrderItemRepositoryInterface $orderItemRepository)
    {
        $this->orderItemRepository = $orderItemRepository;
    }

    public function execute(Observer $observer): self
    {
        /** @var Item $orderItem */
        $orderItem = $observer->getData('item');

        return $this;
    }
}
