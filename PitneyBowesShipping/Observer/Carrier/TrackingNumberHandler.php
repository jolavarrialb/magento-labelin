<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesShipping\Observer\Carrier;

use Labelin\PitneyBowesRestApi\Api\Data\ShipmentPitneyRepositoryInterface;
use Labelin\PitneyBowesRestApi\Model\ShipmentPitney;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Model\Order\Shipment;
use Magento\Sales\Model\Order\Shipment\TrackFactory;
use Magento\Sales\Model\Order\Shipment\Track;

class TrackingNumberHandler implements ObserverInterface
{
    /** @var TrackFactory */
    protected $trackFactory;

    /** @var ShipmentPitneyRepositoryInterface */
    protected $shipmentPitneyBowesRepository;

    /** @var SearchCriteriaBuilder */
    protected $searchCriteriaBuilder;

    /** @var EventManager */
    protected $eventManager;

    public function __construct(
        TrackFactory $trackFactory,
        ShipmentPitneyRepositoryInterface $shipmentPitneyBowesRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        EventManager $eventManager
    ) {
        $this->trackFactory = $trackFactory;
        $this->shipmentPitneyBowesRepository = $shipmentPitneyBowesRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->eventManager = $eventManager;
    }

    public function execute(Observer $observer): self
    {
        /** @var Shipment $shipment */
        $shipment = $observer->getEvent()->getShipment();

        if (!$shipment->getId()) {
            return $this;
        }

        $trackingNumber = $this->getTrackingNumber($shipment);
        $carrierCode = $shipment->getOrder()->getShippingMethod();
        $carrierTitle = $shipment->getOrder()->getShippingDescription();

        if (!$trackingNumber || !$carrierCode) {
            return $this;
        }

        $track = $this->initTrack()
            ->setNumber($trackingNumber)
            ->setCarrierCode($carrierCode)
            ->setTitle($carrierTitle);

        $shipment->addTrack($track);

        $this->eventManager->dispatch('labelin_shipping_tracking_number_after', [
            'tracking_number' => $trackingNumber,
            'shipment' => $shipment,
        ]);

        return $this;
    }

    protected function initTrack(array $data = []): Track
    {
        return $this->trackFactory->create($data);
    }

    protected function getTrackingNumber(Shipment $shipment): ?string
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('shipment_id', $shipment->getId(), 'eq')
            ->create();

        $shipments = $this->shipmentPitneyBowesRepository->getList($searchCriteria)->getItems();

        if (empty($shipments)) {
            return null;
        }

        /** @var ShipmentPitney $shipment */
        $shipment = current($shipments);

        return $shipment->getTrackingId();
    }
}
