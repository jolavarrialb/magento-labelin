<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Observer\Order\Item\Premade;

use Labelin\ProductionTicket\Model\Order\Item;
use Labelin\Sales\Helper\Product\Premade;
use Labelin\Sales\Model\Order;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Api\OrderItemRepositoryInterface;

class PremadeItemStatusInProduction implements ObserverInterface
{
    /** @var OrderItemRepositoryInterface */
    protected $orderItemRepository;

    /** @var EventManager */
    protected $eventManager;

    /** @var Premade  */
    protected $premadeHelper;

    public function __construct(
        OrderItemRepositoryInterface $orderItemRepository,
        EventManager $eventManager,
        Premade $premadeHelper
    )
    {
        $this->orderItemRepository = $orderItemRepository;
        $this->eventManager = $eventManager;
        $this->premadeHelper = $premadeHelper;
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
            if ($this->premadeHelper->isPremade($item) && !$item->isInProduction()) {
                $item->setData('is_in_production', true);

                $this->orderItemRepository->save($item);
            }
        }

        return $this;
    }
}
