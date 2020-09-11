<?php

declare(strict_types=1);

namespace Labelin\Sales\Observer\Artwork;

use Labelin\Sales\Model\Artwork\Email\Sender\UpdateByCustomerSender;
use Labelin\Sales\Model\Order\Item;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class CustomerUploadHandler implements ObserverInterface
{
    /** @var UpdateByCustomerSender */
    protected $sender;

    public function __construct(UpdateByCustomerSender $sender)
    {
        $this->sender = $sender;
    }

    public function execute(Observer $observer): self
    {
        /** @var Item $item */
        $item = $observer->getData('order_item');

        $this->sender->send($item);

        return $this;
    }
}
