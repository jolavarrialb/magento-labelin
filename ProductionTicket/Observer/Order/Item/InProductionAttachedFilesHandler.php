<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Observer\Order\Item;

use Labelin\ProductionTicket\Helper\ProductionTicketImage;
use Labelin\ProductionTicket\Helper\ProductionTicketPdf;
use Labelin\ProductionTicket\Model\Order\Item;
use Labelin\Sales\Helper\Product\Premade;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class InProductionAttachedFilesHandler implements ObserverInterface
{
    /** @var ProductionTicketImage */
    protected $ticketImageHelper;

    /** @var ProductionTicketPdf  */
    protected $ticketPdfHelper;

    /** @var Premade  */
    protected $premadeHelper;

    public function __construct(
        ProductionTicketImage $ticketImageHelper,
        ProductionTicketPdf $ticketPdfHelper,
        Premade $premadeHelper
    ) {
        $this->ticketImageHelper = $ticketImageHelper;
        $this->ticketPdfHelper = $ticketPdfHelper;

        $this->premadeHelper = $premadeHelper;
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

        if ($this->premadeHelper->isPremade($item)) {
            return $this;
        }

        /** Create Image from Item Product Options */
        $this->ticketImageHelper->createInProductionTicketAttachment($item);

        /** Create Pdf from Item Product Options */
        $this->ticketPdfHelper->createInProductionTicketAttachment($item);

        return $this;
    }
}
