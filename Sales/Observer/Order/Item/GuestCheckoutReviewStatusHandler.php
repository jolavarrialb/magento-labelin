<?php

declare(strict_types=1);

namespace Labelin\Sales\Observer\Order\Item;

use Labelin\ProductionTicket\Helper\ProductionTicketImage as ProductionTicketImageHelper;
use Labelin\Sales\Helper\Artwork as ArtworkHelper;
use Labelin\Sales\Model\Order;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class GuestCheckoutReviewStatusHandler implements ObserverInterface
{
    /** @var ArtworkHelper */
    protected $artworkHelper;

    /** @var ProductionTicketImageHelper */
    protected $productionTicketImageHelper;

    public function __construct(
        ArtworkHelper               $artworkHelper,
        ProductionTicketImageHelper $productionTicketImageHelper
    ) {
        $this->artworkHelper = $artworkHelper;
        $this->productionTicketImageHelper = $productionTicketImageHelper;
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
                $this->productionTicketImageHelper->createInProductionTicketAttachment($item);
            }
        }

        return $this;
    }
}
