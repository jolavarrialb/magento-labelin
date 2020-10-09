<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Observer\Order\Item;

use Labelin\ProductionTicket\Helper\ProductionTicket;
use Labelin\ProductionTicket\Model\Order\Item;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class CreateInProductionOrderItemAttachedFilesHandler implements ObserverInterface
{
    /** @var ProductionTicket */
    protected $productionTicketHelper;

    public function __construct(
        ProductionTicket $productionTicketHelper
    ) {
        $this->productionTicketHelper = $productionTicketHelper;
    }

    /**
     * @param Observer $observer
     * @return $this
     * @throws \Exception
     */
    public function execute(Observer $observer): self
    {
        /** @var Item $item */
        $item = $observer->getData('item');
        if (!$item) {
            return $this;
        }

        /** Create Image from Item Product Options */
        $this->productionTicketHelper->createInProductionTicketImageFile($item);

        /** Create Pdf from Item Product Options */
        $this->productionTicketHelper->createInProductionTicketPdfFile($item);

        return $this;
    }

}
