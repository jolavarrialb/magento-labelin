<?php

declare(strict_types=1);

namespace Labelin\Sales\Observer\Order\Item;

use Labelin\Sales\Helper\Artwork as ArtworkHelper;
use Labelin\Sales\Model\Artwork\Email\Sender\GuestCheckout\Welcome\WithArtworkSender;
use Labelin\Sales\Model\Artwork\Email\Sender\GuestCheckout\Welcome\WithoutArtworkSender;
use Labelin\Sales\Model\Order;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class GuestCheckoutReviewStatusHandler implements ObserverInterface
{
    /** @var WithArtworkSender */
    protected $withArtworkSender;

    /** @var WithoutArtworkSender */
    protected $withoutArtworkSender;

    /** @var ArtworkHelper */
    protected $artworkHelper;

    public function __construct(
        WithArtworkSender $withArtworkSender,
        WithoutArtworkSender $withoutArtworkSender,
        ArtworkHelper $artworkHelper
    ) {
        $this->withArtworkSender = $withArtworkSender;
        $this->withoutArtworkSender = $withoutArtworkSender;

        $this->artworkHelper = $artworkHelper;
    }

    public function execute(Observer $observer): self
    {
        /** @var Order $order */
        $order = $observer->getData('order');

        if (!$order->getCustomerIsGuest()) {
            return $this;
        }

        foreach ($order->getAllItems() as $item) {
            /** @var Order\Item $item */
            if ($item->getProductType() !== Configurable::TYPE_CODE) {
                continue;
            }

            if ($this->artworkHelper->isArtworkAttachedToOrderItem($item)) {
                $this->withArtworkSender->send($item);
            } else {
                $this->withoutArtworkSender->send($item);
            }
        }

        return $this;
    }
}
