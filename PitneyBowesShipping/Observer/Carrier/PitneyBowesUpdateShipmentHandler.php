<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesShipping\Observer\Carrier;

use Labelin\PitneyBowesRestApi\Api\Data\ShipmentPitneyRepositoryInterface;
use Labelin\PitneyBowesRestApi\Model\ShipmentPitney;
use Labelin\PitneyBowesShipping\Helper\Shipping as ShippingHelper;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Model\Order\Shipment;
use Psr\Log\LoggerInterface;

class PitneyBowesUpdateShipmentHandler implements ObserverInterface
{
    /** @var ShippingHelper */
    protected $shippingHelper;

    /** @var ShipmentPitneyRepositoryInterface */
    protected $shipmentPitneyBowesRepository;

    /** @var SearchCriteriaBuilder */
    protected $searchCriteriaBuilder;

    /** @var LoggerInterface */
    protected $logger;

    public function __construct(
        ShippingHelper $shippingHelper,
        ShipmentPitneyRepositoryInterface $shipmentPitneyRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        LoggerInterface $logger
    ) {
        $this->shippingHelper = $shippingHelper;
        $this->shipmentPitneyBowesRepository = $shipmentPitneyRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->logger = $logger;
    }

    public function execute(Observer $observer): self
    {
        /** @var Shipment $shipment */
        $shipment = $observer->getEvent()->getShipment();

        if (!$this->shippingHelper->isPitneyBowesShippingByShipment($shipment)) {
            return $this;
        }

        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('order_id', $shipment->getOrderId(), 'eq')
            ->create();

        $shipments = $this->shipmentPitneyBowesRepository->getList($searchCriteria)->getItems();

        if (empty($shipments)) {
            return $this;
        }

        foreach ($shipments as $pitneyBowesShipment) {
            /** @var ShipmentPitney $pitneyBowesShipment */
            $pitneyBowesShipment->setShipmentId((int)$shipment->getId());

            try {
                $this->shipmentPitneyBowesRepository->save($pitneyBowesShipment);
            } catch (\Exception $exception) {
                $this->logger->error($exception->getMessage());
            }
        }

        return $this;
    }
}
