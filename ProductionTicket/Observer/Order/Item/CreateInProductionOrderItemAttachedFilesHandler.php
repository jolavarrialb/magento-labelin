<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Observer\Order\Item;

use Labelin\ProductionTicket\Helper\ProductionTicketImage;
use Labelin\ProductionTicket\Helper\ProductionTicketPdf;
use Labelin\ProductionTicket\Model\Order\Item;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class CreateInProductionOrderItemAttachedFilesHandler implements ObserverInterface
{
    /** @var ProductionTicketImage */
    protected $ticketImageHelper;

    /** @var ProductionTicketPdf  */
    protected $ticketPdfHelper;

    public function __construct(
        ProductionTicketImage $ticketImageHelper,
        ProductionTicketPdf $ticketPdfHelper
    ) {
        $this->ticketImageHelper = $ticketImageHelper;
        $this->ticketPdfHelper = $ticketPdfHelper;
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
        $this->ticketImageHelper->createInProductionTicketImage($item);

        /** Create Pdf from Item Product Options */
        $this->ticketPdfHelper->createInProductionTicketPdf($item);

        return $this;
    }

}
