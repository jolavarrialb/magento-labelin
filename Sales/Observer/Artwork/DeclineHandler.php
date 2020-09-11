<?php

declare(strict_types=1);

namespace Labelin\Sales\Observer\Artwork;

use Labelin\Sales\Model\Artwork\Email\Sender\DeclineSender;
use Labelin\Sales\Model\Order\Item;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class DeclineHandler implements ObserverInterface
{
    /** @var DeclineSender */
    protected $declineSender;

    public function __construct(DeclineSender $declineSender)
    {
        $this->declineSender = $declineSender;
    }

    public function execute(Observer $observer): self
    {
        /** @var Item $item */
        $item = $observer->getData('order_item');
        $comment = $observer->getData('comment');

        $this->declineSender->send($item, $comment);

        return $this;
    }
}
