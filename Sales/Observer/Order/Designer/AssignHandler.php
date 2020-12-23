<?php

declare(strict_types=1);

namespace Labelin\Sales\Observer\Order\Designer;

use Labelin\Sales\Model\Order\Email\Sender\AssignDesignerSender as Sender;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class AssignHandler implements ObserverInterface
{
    /** @var Sender */
    protected $sender;

    public function __construct(Sender $sender)
    {
        $this->sender = $sender;
    }

    public function execute(Observer $observer): self
    {
        $orders = $observer->getEvent()->getData('orders');
        $designer = $observer->getEvent()->getData('designer');

        if (empty($orders) || !$designer) {
            return $this;
        }

        foreach ($orders as $order) {
            $this->sender
                ->setOrder($order)
                ->setDesigner($designer)
                ->send();
        }

        return $this;
    }
}
