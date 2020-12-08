<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesShipping\Observer\Carrier;

use Labelin\PitneyBowesShipping\Model\Email\Sender\TrackInfoSender;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class TrackingNumberNotificationHandler implements ObserverInterface
{
    /** @var TrackInfoSender */
    protected $sender;

    public function __construct(TrackInfoSender $sender)
    {
        $this->sender = $sender;
    }

    public function execute(Observer $observer): self
    {
        $trackingNumber = $observer->getEvent()->getData('tracking_number');
        $shipment = $observer->getEvent()->getData('shipment');

        if (!$trackingNumber || !$shipment->getId()) {
            return $this;
        }

        $this->sender->send($trackingNumber, $shipment);

        return $this;
    }
}
