<?php

declare(strict_types=1);

namespace Labelin\Sales\Observer\Artwork;

use Labelin\Sales\Model\Artwork\Email\Sender\ApproveSender;
use Labelin\Sales\Model\Order\Item;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class ApproveHandler implements ObserverInterface
{
    /** @var ApproveSender */
    protected $approveSender;

    public function __construct(ApproveSender $approveSender)
    {
        $this->approveSender = $approveSender;
    }

    public function execute(Observer $observer): self
    {
        /** @var Item $item */
        $item = $observer->getData('order_item');

        $this->approveSender->send($item);

        return $this;
    }
}
