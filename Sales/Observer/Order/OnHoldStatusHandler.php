<?php

declare(strict_types=1);

namespace Labelin\Sales\Observer\Order;

use Labelin\Sales\Helper\Artwork as ArtworkHelper;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Item;

class OnHoldStatusHandler implements ObserverInterface
{
    /** @var ArtworkHelper */
    protected $artworkHelper;

    public function __construct(ArtworkHelper $artworkHelper)
    {
        $this->artworkHelper = $artworkHelper;
    }

    public function execute(Observer $observer): self
    {
        /** @var Order $order */
        $order = $observer->getOrder();

        if ($this->artworkHelper->isArtworkAttachedToOrder($order)) {
            $order
                ->setState(Order::STATE_HOLDED)
                ->setStatus(Order::STATE_HOLDED);
        }

        return $this;
    }
}
