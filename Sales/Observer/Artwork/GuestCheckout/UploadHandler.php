<?php

declare(strict_types=1);

namespace Labelin\Sales\Observer\Artwork\GuestCheckout;

use Labelin\ProductionTicket\Helper\ProductionTicketImage as ProductionTicketImageHelper;
use Labelin\Sales\Model\Artwork\Email\Sender\GuestCheckout\ApproveSender;
use Labelin\Sales\Model\Order\Item;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Message\ManagerInterface as MessageManager;

class UploadHandler implements ObserverInterface
{
    /** @var EventManager */
    protected $eventManager;

    /** @var MessageManager */
    protected $messageManager;

    /** @var ProductionTicketImageHelper */
    protected $productionTicketImageHelper;

    /** @var ApproveSender */
    protected $sender;

    public function __construct(
        EventManager $eventManager,
        MessageManager $messageManager,
        ProductionTicketImageHelper $productionTicketImageHelper,
        ApproveSender $sender
    ) {
        $this->eventManager = $eventManager;
        $this->messageManager = $messageManager;
        $this->productionTicketImageHelper = $productionTicketImageHelper;
        $this->sender = $sender;
    }

    public function execute(Observer $observer): self
    {
        /** @var Item $item */
        $item = $observer->getData('item');

        try {
            $this->eventManager->dispatch('labelin_sales_order_item_artwork_update', ['item' => $item]);

            $item->approveArtworkByDesigner();

            $this->productionTicketImageHelper->createInProductionTicketAttachment($item);

            $this->sender->send($item);

            $this->messageManager->addSuccessMessage(__('Artwork was successfully updated.'));
        } catch (LocalizedException $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());
        }

        return $this;
    }
}
