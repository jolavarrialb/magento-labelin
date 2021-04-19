<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Observer;

use Labelin\ProductionTicket\Model\Email\Sender\ShipperOrderSender;
use Labelin\Sales\Model\Order;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class ShipperEmailHandler implements ObserverInterface
{
    /** @var ShipperOrderSender */
    protected $sender;

    public function __construct(
        ShipperOrderSender $sender
    ) {
        $this->sender = $sender;
    }

    public function execute(Observer $observer): self
    {
        /** @var Order $order */
        $order = $observer->getData('order');

        $this->sender->send($order);

        return $this;
    }
}
