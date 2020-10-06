<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Observer\Order;

use Labelin\ProductionTicket\Model\Order\Item;
use Labelin\Sales\Model\Order;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Api\OrderItemRepositoryInterface;

class InProductionStatusHandler implements ObserverInterface
{
    /** @var OrderItemRepositoryInterface */
    protected $orderItemRepository;

    /** @var EventManager */
    protected $eventManager;

    public function __construct(OrderItemRepositoryInterface $orderItemRepository, EventManager $eventManager)
    {
        $this->orderItemRepository = $orderItemRepository;
        $this->eventManager = $eventManager;
    }

    /**
     * @param Observer $observer
     *
     * @return $this
     * @throws LocalizedException
     */
    public function execute(Observer $observer): self
    {
        /** @var Order $order */
        $order = $observer->getData('order');

        if (!$order) {
            return $this;
        }

        foreach ($order->getAllItems() as $item) {
            /** @var Item $item */
            if ($item->getProductType() === Configurable::TYPE_CODE && !$item->isInProduction()) {
                $item->markAsInProduction();
                $this->orderItemRepository->save($item);

                $this->eventManager->dispatch('labelin_order_item_production_status_after', ['item' => $item]);
            }
        }

        return $this;
    }
}
