<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesShipping\Observer\Carrier;

use Labelin\PitneyBowesShipping\Helper\Shipping as ShippingHelper;
use Labelin\PitneyBowesShipping\Model\Email\Sender\TrackInfoSender;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Model\Order\Shipment;

class TrackingNotificationsHandler implements ObserverInterface
{
    /** @var TrackInfoSender */
    protected $sender;

    /** @var ShippingHelper */
    protected $shippingHelper;

    public function __construct(TrackInfoSender $sender, ShippingHelper $shippingHelper)
    {
        $this->sender = $sender;
        $this->shippingHelper = $shippingHelper;
    }

    public function execute(Observer $observer): self
    {
        /** @var Shipment $shipment */
        $shipment = $observer->getEvent()->getShipment();

        if (!$this->shippingHelper->isPitneyBowesShippingByShipment($shipment)) {
            return $this;
        }

        foreach ($shipment->getAllTracks() as $track) {
            /** @var Shipment\Track $track */
            $this->sender->send($track, $shipment);
        }

        return $this;
    }
}
