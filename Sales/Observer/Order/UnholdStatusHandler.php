<?php

declare(strict_types=1);

namespace Labelin\Sales\Observer\Order;

use Labelin\Sales\Helper\Artwork as ArtworkHelper;
use Labelin\Sales\Model\Order;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;
use Labelin\Sales\Model\Order\Item as OrderItem;
use Magento\Sales\Model\OrderRepository;

class UnholdStatusHandler implements ObserverInterface
{
    /** @var ArtworkHelper */
    protected $artworkHelper;

    /** @var OrderRepository */
    protected $orderRepository;

    public function __construct(ArtworkHelper $artworkHelper, OrderRepository $orderRepository)
    {
        $this->artworkHelper = $artworkHelper;
        $this->orderRepository = $orderRepository;
    }

    /**
     * @param Observer $observer
     *
     * @return $this
     * @throws LocalizedException
     */
    public function execute(Observer $observer): self
    {
        /** @var OrderItem $orderItem */
        $orderItem = $observer->getData('order_item');

        /** @var Order $order */
        $order = $orderItem->getOrder();

        if ($order->getStatus() !== Order::STATE_HOLDED) {
            return $this;
        }

        if (!$this->artworkHelper->isArtworkAttachedToOrder($order)) {
            return $this;
        }

        $order->unhold();

        try {
            $this->orderRepository->save($order);
        } catch (\Exception $exception) {
            throw new LocalizedException(__($exception->getMessage()));
        }

        return $this;
    }
}
