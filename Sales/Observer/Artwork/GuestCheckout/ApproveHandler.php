<?php

declare(strict_types=1);

namespace Labelin\Sales\Observer\Artwork\GuestCheckout;

use Labelin\Sales\Model\Order\Item;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Message\ManagerInterface as MessageManager;

class ApproveHandler implements ObserverInterface
{
    /** @var MessageManager */
    protected $messageManager;

    public function __construct(MessageManager $messageManager)
    {
        $this->messageManager = $messageManager;
    }

    public function execute(Observer $observer): self
    {
        /** @var Item $item */
        $item = $observer->getData('item');

        try {
            $item->setData('is_artwork_approved', 1);
            $item->setData('artwork_approval_date', new \Zend_Db_Expr('NOW()'));
            $item->approveArtworkByDesigner();

            $this->messageManager->addSuccessMessage(__('Artwork was successfully approved.'));
        } catch (LocalizedException $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());
        }

        return $this;
    }
}
