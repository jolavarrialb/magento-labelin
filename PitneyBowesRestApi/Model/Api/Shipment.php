<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesRestApi\Model\Api;

use Labelin\PitneyBowesOfficialApi\Model\Api\Model\Address;
use Labelin\PitneyBowesOfficialApi\Model\Api\Model\Document;
use Labelin\PitneyBowesOfficialApi\Model\Api\Model\Parameter;
use Labelin\PitneyBowesOfficialApi\Model\Api\Model\Parcel;
use Labelin\PitneyBowesOfficialApi\Model\Api\Model\ParcelDimension;
use Labelin\PitneyBowesOfficialApi\Model\Api\Model\ParcelWeight;
use Labelin\PitneyBowesOfficialApi\Model\Api\Model\Rate;
use Labelin\PitneyBowesOfficialApi\Model\Api\Model\Services;
use Labelin\PitneyBowesOfficialApi\Model\Api\Model\Shipment as ShipmentApiResponse;
use Labelin\PitneyBowesOfficialApi\Model\Api\Model\SpecialService;
use Labelin\PitneyBowesOfficialApi\Model\Api\Model\SpecialServiceCodes;
use Labelin\PitneyBowesOfficialApi\Model\Configuration as OauthConfiguration;
use Labelin\PitneyBowesOfficialApi\Model\Shipping\ShipmentApi;
use Labelin\PitneyBowesRestApi\Api\Data\AddressDtoInterface;
use Labelin\PitneyBowesRestApi\Api\Data\ParcelDtoInterface;
use Labelin\PitneyBowesRestApi\Api\Data\ShipmentPitneyInterface;
use Labelin\PitneyBowesRestApi\Api\ShipmentInterface;
use Labelin\PitneyBowesRestApi\Model\Api\Data\ShipmentResponseDto;
use Labelin\PitneyBowesRestApi\Model\Api\Data\ShipmentsRatesDto;
use Labelin\PitneyBowesRestApi\Model\ShipmentPitney;
use Labelin\PitneyBowesRestApi\Model\ShipmentPitneyFactory;
use Labelin\PitneyBowesRestApi\Model\ShipmentPitneyRepository;
use Labelin\PitneyBowesShipping\Helper\Config\FreeShippingConfig as ConfigHelper;
use Magento\Framework\Math\Random;
use Psr\Log\LoggerInterface;

class Shipment implements ShipmentInterface
{
    /** @var ConfigHelper */
    protected $configHelper;

    /** @var OauthConfiguration */
    protected $oauthConfiguration;

    /** @var ShipmentPitneyRepository */
    protected $shipmentPitneyRepository;

    /** @var ShipmentPitneyFactory */
    protected $shipmentPitneyFactory;

    /** @var Random */
    protected $mathRandom;

    /** @var LoggerInterface */
    protected $logger;

    public function __construct(
        ConfigHelper $configHelper,
        OauthConfiguration $oauthConfiguration,
        ShipmentPitneyRepository $shipmentPitneyRepository,
        ShipmentPitneyFactory $shipmentPitneyFactory,
        Random $mathRandom,
        LoggerInterface $logger
    ) {
        $this->configHelper = $configHelper;
        $this->oauthConfiguration = $oauthConfiguration;

        $this->shipmentPitneyFactory = $shipmentPitneyFactory;
        $this->shipmentPitneyRepository = $shipmentPitneyRepository;

        $this->mathRandom = $mathRandom;
        $this->logger = $logger;
    }

    /**
     * @param AddressDtoInterface $fromAddress
     * @param AddressDtoInterface $toAddress
     * @param ParcelDtoInterface  $parcel
     * @param ShipmentsRatesDto   $rates
     * @param int                 $orderId
     * @param int                 $packageId
     *
     * @return \Labelin\PitneyBowesRestApi\Api\Data\ShipmentResponseDtoInterface
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function requestShipmentLabel(
        AddressDtoInterface $fromAddress,
        AddressDtoInterface $toAddress,
        ParcelDtoInterface $parcel,
        ShipmentsRatesDto $rates,
        int $orderId,
        int $packageId
    ) {
        $result = new ShipmentResponseDto();
        $xPbTransactionId = sprintf('%s_PKG_%s_%s', $orderId, $packageId, $this->mathRandom->getRandomString(10));

        $shipment = new ShipmentApiResponse([
            'from_address' => new Address($fromAddress->toShippingOptionsArray()),
            'to_address' => new Address($toAddress->toShippingOptionsArray()),
            'parcel' => new Parcel([
                'weight' => new ParcelWeight($parcel->toWeightArray()),
                'dimension' => new ParcelDimension($parcel->toDimensionsArray()),
            ]),
            'rates' => [
                new Rate([
                    'carrier' => $rates->getCarrier(),
                    'parcel_type' => $rates->getParcelType(),
                    'service_id' => $rates->getServiceId(),
                    'induction_postal_code' => $rates->getInductionPostalCode(),
                    'special_services' => $this->getSpecialServices($rates->getServiceId()),

                ]),
            ],
            'shipment_options' => [
                new Parameter([
                    'name' => 'SHIPPER_ID',
                    'value' => $this->configHelper->getMerchantId(),
                ]),
                new Parameter([
                    'name' => 'ADD_TO_MANIFEST',
                    'value' => true,
                ]),
            ],
            'documents' => [
                new Document([
                    'type' => 'SHIPPING_LABEL',
                    'content_type' => Document::CONTENT_TYPE_BASE64,
                    'size' => Document::SIZE__4_X6,
                    'file_format' => Document::FILE_FORMAT_PNG,
                    'print_dialog_option' => Document::PRINT_DIALOG_OPTION_NO_PRINT_DIALOG,
                ]),
            ],
        ]);

        $this->oauthConfiguration->setAccessToken($this->configHelper->getApiAccessToken());

        $this->logger->info('REQUEST:NEW_SHIPMENT');
        $this->logger->info($shipment);

        try {
            $shipmentRequest = (new ShipmentApi($this->oauthConfiguration))
                ->createShipmentLabel($xPbTransactionId, $shipment);
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());

            return $result->setErrors($exception->getMessage());
        }

        $this->logger->info('RESPONSE:NEW_SHIPMENT');
        $this->logger->info($shipmentRequest);


        $shipmentPitneyBowes = $this->saveShipmentRequest($orderId, $shipmentRequest);

        $result->setShippingLabelContent($shipmentPitneyBowes->getLabelLink());
        $result->setTrackingNumber($shipmentPitneyBowes->getTrackingId());

        return $result;
    }

    /**
     * @param string $serviceId
     *
     * @return array
     */
    protected function getSpecialServices(string $serviceId): array
    {
        $specialServices = [];
        switch ($serviceId) {
            case Services::EM:
                $specialServices[] = new SpecialService([
                    'special_service_id' => SpecialServiceCodes::SERVICE_INS,
                    'input_parameters' => [
                        new Parameter(
                            [
                                'name' => 'INPUT_VALUE',
                                "value" => 1,
                            ]
                        ),
                    ],
                ]);

                break;
            default:
                $specialServices[] = new SpecialService([
                    'special_service_id' => SpecialServiceCodes::SERVICE_DEL_CON,
                    'input_parameters' => [
                        new Parameter(
                            [
                                'name' => 'INPUT_VALUE',
                                "value" => 0,
                            ]
                        ),
                    ],
                ]);
        }

        return $specialServices;
    }

    /**
     * @param mixed               $orderId
     * @param ShipmentApiResponse $result
     *
     * @return ShipmentPitneyInterface
     */
    protected function saveShipmentRequest($orderId, ShipmentApiResponse $result): ShipmentPitneyInterface
    {
        $shipmentPitneyBowes = $this->initShipmentPitneyBowes()
            ->setOrderId($orderId)
            ->setShipmentId($orderId)
            ->setResponse($result->__toString())
            ->setTrackingId($result->getParcelTrackingNumber())
            ->setLabelLink($this->getShippingLabelContent($result));

        return $this->shipmentPitneyRepository->save($shipmentPitneyBowes);
    }

    /**
     * @param ShipmentApiResponse $result
     *
     * @return string
     */
    protected function getShippingLabelContent(ShipmentApiResponse $result): string
    {
        /** @var Document $document */
        foreach ($result->getDocuments() as $document) {
            if ($document->getType() === 'SHIPPING_LABEL' &&
                $document->getContentType() === Document::CONTENT_TYPE_BASE64
            ) {
                return base64_decode(current($document->getPages())->getContents());
            }
        }

        return '';
    }

    protected function initShipmentPitneyBowes(array $data = []): ShipmentPitney
    {
        return $this->shipmentPitneyFactory->create($data);
    }
}
