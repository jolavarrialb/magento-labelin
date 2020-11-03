<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Observer\Order\Item;

use Labelin\Sales\Model\Order\Item;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Labelin\ProductionTicket\Model\Email\Sender\ArtworkInProductionSender;

class InProductionStatusEmailHandler implements ObserverInterface
{
    /** @var ArtworkInProductionSender */
    protected $inProductionSender;

    public function __construct(
        ArtworkInProductionSender $inProductionSender
    ) {
        $this->inProductionSender = $inProductionSender;
    }

    public function execute(Observer $observer): self
    {
        /** @var Item $item */
        $item = $observer->getData('item');

        $this->inProductionSender->send($item);

        return $this;
    }
}
