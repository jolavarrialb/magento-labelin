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

    /** @var ShipmentRepositoryInterface */
    protected $shipmentRepository;

    /** @var OrderRepositoryInterface */
    protected $orderRepository;

    /** @var OrderItemRepositoryInterface */
    protected $orderItemRepository;

    /** @var LoggerInterface */
    protected $logger;

    /** @var array */
    protected $rates = [];

    public function __construct(
        ConfigHelper $configHelper,
        OauthConfiguration $oauthConfiguration,
        ShipmentRepositoryInterface $shipmentRepository,
        OrderRepositoryInterface $orderRepository,
        OrderItemRepositoryInterface $orderItemRepository,
        LoggerInterface $logger
    ) {
        $this->configHelper = $configHelper;
        $this->oauthConfiguration = $oauthConfiguration;
        $this->shipmentRepository = $shipmentRepository;
        $this->orderRepository = $orderRepository;
        $this->orderItemRepository = $orderItemRepository;
        $this->logger = $logger;
    }

    /**
     * @param string $pitneyBowesShipmentId
     * @param int $magentoShipmentId
     *
     * @return \Labelin\PitneyBowesRestApi\Api\Data\CancelShipmentDtoInterface|null
     */
    public function cancelShipment(string $pitneyBowesShipmentId, int $magentoShipmentId)
    {
        $this->oauthConfiguration->setAccessToken($this->configHelper->getApiAccessToken());

        $this->logger->info('REQUEST:');
        $this->logger->info($pitneyBowesShipmentId);

        try {
            $response = (new ShipmentApi($this->oauthConfiguration))->cancelShipment(
                $magentoShipmentId,
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

        $this->deleteShipment($magentoShipmentId);

        return (new CancelShipmentDto())
            ->setCancelInitiator($response->getCancelInitiator())
            ->setCarrier($response->getCarrier())
            ->setParcelTrackingNumber($response->getParcelTrackingNumber())
            ->setStatus($response->getStatus())
            ->setTotalCarrierCharge($response->getTotalCarrierCharge());
    }

    protected function deleteShipment(int $shipmentId): void
    {
        try {
            $shipment = $this->shipmentRepository->get($shipmentId);

            if (!$shipment) {
                return;
            }

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

            $this->shipmentRepository->delete($shipment);
        } catch (\Exception $exception) {
            $this->logger->critical($exception->getMessage());
        }
    }
}
