<?php

declare(strict_types=1);

namespace Labelin\Sales\Observer\Artwork;

use Labelin\Sales\Model\Artwork\Email\Sender\UpdateByDesignerSender;
use Labelin\Sales\Model\Order\Item;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class DesignerUploadHandler implements ObserverInterface
{
    /** @var UpdateByDesignerSender */
    protected $sender;

    public function __construct(UpdateByDesignerSender $sender)
    {
        $this->sender = $sender;
    }

    public function execute(Observer $observer): self
    {
        /** @var Item $item */
        $item = $observer->getData('order_item');
        $comment = $observer->getData('comment');

        $this->sender->send($item, $comment);

        return $this;
    }
}
