<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Observer\Order;

use Labelin\ProductionTicket\Model\Order;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Api\OrderRepositoryInterface;

class PremadeOrderToProduction implements ObserverInterface
{
    /** @var OrderRepositoryInterface */
    protected $orderRepository;

    public function __construct(
        OrderRepositoryInterface $orderRepository
    ) {
        $this->orderRepository = $orderRepository;
    }

    public function execute(Observer $observer): self
    {
        /** @var Order $order */
        $order = $observer->getData('order');

        if (!$order) {
            return $this;
        }

        if ($order->isAllItemsReadyForProduction() && Order::STATUS_IN_PRODUCTION !== $order->getStatus()) {
            $order->markAsProduction();
            $this->orderRepository->save($order);
        }

        return $this;
    }
}
