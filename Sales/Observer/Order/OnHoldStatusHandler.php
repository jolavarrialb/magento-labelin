<?php

declare(strict_types=1);

namespace Labelin\Sales\Observer\Order;

use Labelin\Sales\Helper\Artwork as ArtworkHelper;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;

class OnHoldStatusHandler implements ObserverInterface
{
    /** @var ArtworkHelper */
    protected $artworkHelper;

    /** @var OrderRepositoryInterface */
    protected $orderRepository;

    public function __construct(ArtworkHelper $artworkHelper, OrderRepositoryInterface $orderRepository)
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
        /** @var Order $order */
        $order = $observer->getEvent()->getData('order');

        if ($order->getCustomerIsGuest()) {
            return $this;
        }

        if ($this->artworkHelper->isArtworkAttachedToOrder($order)) {
            return $this;
        }

        $order->hold();

        $this->orderRepository->save($order);

        return $this;
    }
}
