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
use Labelin\PitneyBowesOfficialApi\Model\Api\Model\Shipment as ShipmentApiResponse;
use Labelin\PitneyBowesOfficialApi\Model\Api\Model\SpecialService;
use Labelin\PitneyBowesOfficialApi\Model\ApiException;
use Labelin\PitneyBowesOfficialApi\Model\Configuration as OauthConfiguration;
use Labelin\PitneyBowesOfficialApi\Model\Shipping\ShipmentApi;
use Labelin\PitneyBowesRestApi\Api\Data\AddressDtoInterface;
use Labelin\PitneyBowesRestApi\Api\Data\ParcelDtoInterface;
use Labelin\PitneyBowesRestApi\Api\ShipmentInterface;
use Labelin\PitneyBowesRestApi\Helper\Rates as RatesHelper;
use Labelin\PitneyBowesRestApi\Model\Api\Data\ShipmentsRatesDto;
use Labelin\PitneyBowesRestApi\Model\ShipmentPitney;
use Labelin\PitneyBowesRestApi\Model\ShipmentPitneyRepository;
use Labelin\PitneyBowesShipping\Helper\Config\FreeShippingConfig as ConfigHelper;
use Magento\Framework\DataObject;
use Magento\Framework\Serialize\SerializerInterface;
use Psr\Log\LoggerInterface;

class Shipment implements ShipmentInterface
{
    /** @var ConfigHelper */
    protected $configHelper;

    /** @var OauthConfiguration */
    protected $oauthConfiguration;

    /** @var RatesHelper */
    protected $ratesHelper;

    /** @var ShipmentPitneyRepository */
    protected $shipmentPitneyRepository;

    /** @var ShipmentPitney */
    protected $shipmentPitney;

    /** @var SerializerInterface */
    protected $serializer;

    /** @var LoggerInterface  */
    protected $logger;

    public function __construct(
        ConfigHelper $configHelper,
        RatesHelper $ratesHelper,
        OauthConfiguration $oauthConfiguration,
        ShipmentPitneyRepository $shipmentPitneyRepository,
        ShipmentPitney $shipmentPitney,
        SerializerInterface $serializer,
        LoggerInterface $logger
    ) {
        $this->configHelper = $configHelper;
        $this->ratesHelper = $ratesHelper;
        $this->oauthConfiguration = $oauthConfiguration;

        $this->shipmentPitney = $shipmentPitney;
        $this->shipmentPitneyRepository = $shipmentPitneyRepository;

        $this->serializer = $serializer;
        $this->logger = $logger;
    }

    /**
     * @param AddressDtoInterface $fromAddress
     * @param AddressDtoInterface $toAddress
     * @param ParcelDtoInterface $parcel
     * @param ShipmentsRatesDto $rates
     * @param DataObject $request
     * @return DataObject
     *
     * @throws ApiException on non-2xx response
     */
    public function requestShipmentLabel(
        AddressDtoInterface $fromAddress,
        AddressDtoInterface $toAddress,
        ParcelDtoInterface $parcel,
        ShipmentsRatesDto $rates,
        DataObject $request
    ) {
        $result = new DataObject();
        $x_pb_transaction_id = sprintf('%s_PKG_%s',$request->getOrderShipment()->getIncrementId(), $request->getPackageId());

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
                    'content_type' => Document::CONTENT_TYPE_URL,
                    'size' => Document::SIZE__4_X6,
                    'file_format' => Document::FILE_FORMAT_PDF,
                    'print_dialog_option' => Document::PRINT_DIALOG_OPTION_NO_PRINT_DIALOG,
                ]),
            ],
        ]);

        $this->oauthConfiguration->setAccessToken($this->configHelper->getApiAccessToken());

        $this->logger->info('REQUEST:NEW_SHIPMENT');
        $this->logger->info($shipment);

        try {
            $shipmentRequest = (new ShipmentApi($this->oauthConfiguration))->createShipmentLabel($x_pb_transaction_id, $shipment);
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());

            return $result->setErrors($exception->getMessage());
        }

        $this->logger->info('RESPONSE:NEW_SHIPMENT');
        $this->logger->info($shipmentRequest);


        $this->saveShipmentRequest($request, $shipmentRequest);

        $result->setShipmentRequest($shipmentRequest);
        $result->setShippingLabelContent($this->shipmentPitney->getLabelLink());
        $result->setTrackingNumber($this->shipmentPitney->getTrackingId());

        return $result;
    }

    /**
     * @param $serviceId
     * @return array
     */
    protected function getSpecialServices($serviceId): array
    {
        $specialServices = [];
        switch ($serviceId) {
            case 'EM':
                $specialServices[] = new SpecialService([
                    'special_service_id' => static::SERVICE_INS,
                    'input_parameters' => [new Parameter(
                        [
                            'name' => 'INPUT_VALUE',
                            "value" => 1,
                        ]
                    )],
                ]);

                break;
            default:
                $specialServices[] = new SpecialService([
                    'special_service_id' => static::SERVICE_DEL_CON,
                    'input_parameters' => [new Parameter(
                        [
                            'name' => 'INPUT_VALUE',
                            "value" => 0,
                        ]
                    )],
                ]);
        }

        return $specialServices;
    }

    /**
     * @param DataObject $request
     * @param $result
     * @return void
     */
    protected function saveShipmentRequest(DataObject $request, $result): void
    {
        $this->shipmentPitney
            ->setOrderId($request->getOrderShipment()->getOrderId())
            ->setResponse($this->serializer->serialize($result->__toString()))
            ->setTrackingId($result->getParcelTrackingNumber())
            ->setLabelLink($this->getShippingLabelUrl($result))
            ->setShipmentId($result->getShipmentId());

        $this->shipmentPitneyRepository->save($this->shipmentPitney);
    }

    /**
     * @param $result
     * @return string
     */
    protected function getShippingLabelUrl($result): string
    {
        /** @var \Labelin\PitneyBowesOfficialApi\Model\Api\Model\Document $document */
        foreach ($result->getDocuments() as $document) {
            if ($document->getType() === 'SHIPPING_LABEL' && $document->getContentType() === 'URL') {
                return $document->getContents();
            }
        }

        return '';
    }
}
