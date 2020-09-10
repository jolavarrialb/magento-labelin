<?php

declare(strict_types=1);

namespace Labelin\Sales\Observer\Order;

use Labelin\Sales\Helper\Artwork as ArtworkHelper;
use Labelin\Sales\Model\Order;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;
use Labelin\Sales\Model\Order\Item as OrderItem;

class UnholdStatusHandler implements ObserverInterface
{
    /** @var ArtworkHelper */
    protected $artworkHelper;

    public function __construct(ArtworkHelper $artworkHelper)
    {
        $this->artworkHelper = $artworkHelper;
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
        $orderItem = $observer->getItem();

        /** @var Order $order */
        $order = $orderItem->getOrder();

        if ($order->getStatus() !== Order::STATE_HOLDED) {
            return $this;
        }

        if (!$this->artworkHelper->isArtworkAttachedToOrder($order)) {
            return $this;
        }

        $order->unhold();

        return $this;
    }
}
