<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesRestApi\Model\Api;

use Labelin\PitneyBowesOfficialApi\Model\Configuration as OauthConfiguration;
use Labelin\PitneyBowesOfficialApi\Model\Shipping\ShipmentApi;
use Labelin\PitneyBowesRestApi\Api\CancelShipmentInterface;
use Labelin\PitneyBowesRestApi\Api\Data\ShipmentPitneyRepositoryInterface;
use Labelin\PitneyBowesRestApi\Model\Api\Data\CancelShipmentDto;
use Labelin\PitneyBowesRestApi\Model\ShipmentPitney;
use Labelin\PitneyBowesShipping\Helper\Config\FreeShippingConfig as ConfigHelper;
use Labelin\Sales\Model\Order;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Sales\Api\OrderItemRepositoryInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Api\ShipmentRepositoryInterface;
use Psr\Log\LoggerInterface;

class CancelShipment implements CancelShipmentInterface
{
    protected const CANCEL_INITIATOR = 'SHIPPER';

    /** @var ConfigHelper */
    protected $configHelper;

    /** @var OauthConfiguration */
    protected $oauthConfiguration;

    /** @var ShipmentPitneyRepositoryInterface */
    protected $shipmentPitneyBowesRepository;

    /** @var ShipmentRepositoryInterface */
    protected $shipmentRepository;

    /** @var OrderRepositoryInterface */
    protected $orderRepository;

    /** @var OrderItemRepositoryInterface */
    protected $orderItemRepository;

    /** @var SearchCriteriaBuilder */
    protected $searchCriteriaBuilder;

    /** @var LoggerInterface */
    protected $logger;

    /** @var array */
    protected $rates = [];

    public function __construct(
        ConfigHelper $configHelper,
        OauthConfiguration $oauthConfiguration,
        ShipmentPitneyRepositoryInterface $shipmentPitneyRepository,
        ShipmentRepositoryInterface $shipmentRepository,
        OrderRepositoryInterface $orderRepository,
        OrderItemRepositoryInterface $orderItemRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        LoggerInterface $logger
    ) {
        $this->configHelper = $configHelper;
        $this->oauthConfiguration = $oauthConfiguration;
        $this->shipmentPitneyBowesRepository = $shipmentPitneyRepository;
        $this->shipmentRepository = $shipmentRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->orderRepository = $orderRepository;
        $this->orderItemRepository = $orderItemRepository;
        $this->logger = $logger;
    }

    /**
     * @param int $shipmentId
     *
     * @return \Labelin\PitneyBowesRestApi\Api\Data\CancelShipmentDtoInterface|null
     */
    public function cancelShipment(int $shipmentId)
    {
        $this->oauthConfiguration->setAccessToken($this->configHelper->getApiAccessToken());

        $this->logger->info('REQUEST:');
        $this->logger->info($shipmentId);

        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('shipment_id', $shipmentId, 'eq')
            ->create();

        $shipments = $this->shipmentPitneyBowesRepository->getList($searchCriteria)->getItems();

        if (empty($shipments)) {
            return null;
        }

        /** @var ShipmentPitney $shipment */
        $shipment = current($shipments);

        if ($shipment->getIsCanceled()) {
            return null;
        }

        try {
            $pitneyBowesShipmentId = json_decode($shipment->getResponse(), true);
            $pitneyBowesShipmentId = $pitneyBowesShipmentId['shipmentId'];

            $response = (new ShipmentApi($this->oauthConfiguration))->cancelShipment(
                $shipment->getId(),
                $pitneyBowesShipmentId,
                true,
                $this->configHelper->getMerchantId(),
                self::CANCEL_INITIATOR
            );
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());

            return null;
        }

        if ($response->getErrorMessages()) {
            $error = current($response->getErrorMessages());
            $this->logger->error($error->getMessage() . ' ' . $error->getAdditionalInfo());

            return null;
        }

        $this->logger->info('RESPONSE:');
        $this->logger->info($response);

        $shipment->setIsCanceled(true);
        $this->shipmentPitneyBowesRepository->save($shipment);

        $this->deleteShipment($shipmentId);

        return (new CancelShipmentDto())
            ->setCancelInitiator($response->getCancelInitiator())
            ->setCarrier($response->getCarrier())
            ->setParcelTrackingNumber($response->getParcelTrackingNumber())
            ->setStatus($response->getStatus())
            ->setTotalCarrierCharge($response->getTotalCarrierCharge());
    }

    protected function deleteShipment(int $shipmentId): bool
    {
        $deleteShipment = false;

        try {
            $shipment = $this->shipmentRepository->get($shipmentId);

            $order = $this->orderRepository->get($shipment->getOrderId());
            $order->setState(Order::STATE_NEW);
            $order->setStatus(Order::STATUS_READY_TO_SHIP);
            $this->orderRepository->save($order);

            foreach ($shipment->getPackages() as $package) {
                $items = $package['items'];

                foreach ($items as $id => $item) {
                    $qty = $item['qty'];
                    $orderItem = $this->orderItemRepository->get($id);
                    $orderItem->setQtyShipped($orderItem->getQtyShipped() - $qty);
                    $this->orderItemRepository->save($orderItem);
                }
            }

            $deleteShipment = $this->shipmentRepository->delete($shipment);
        } catch (\Exception $exception) {
            $this->logger->critical($exception->getMessage());
        }

        return $deleteShipment;
    }
}
