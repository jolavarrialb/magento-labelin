<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesRestApi\Model\Api;

use Labelin\PitneyBowesOfficialApi\Model\Api\Model\Address;
use Labelin\PitneyBowesOfficialApi\Model\Api\Model\Errors;
use Labelin\PitneyBowesOfficialApi\Model\Api\Model\Parameter;
use Labelin\PitneyBowesOfficialApi\Model\Api\Model\Parcel;
use Labelin\PitneyBowesOfficialApi\Model\Api\Model\ParcelDimension;
use Labelin\PitneyBowesOfficialApi\Model\Api\Model\ParcelWeight;
use Labelin\PitneyBowesOfficialApi\Model\Api\Model\Rate;
use Labelin\PitneyBowesOfficialApi\Model\Api\Model\Shipment as ShipmentApiResponse;
use Labelin\PitneyBowesOfficialApi\Model\ApiException;
use Labelin\PitneyBowesOfficialApi\Model\Configuration as OauthConfiguration;
use Labelin\PitneyBowesOfficialApi\Model\Shipping\ShipmentApi;
use Labelin\PitneyBowesRestApi\Api\Data\AddressDtoInterface;
use Labelin\PitneyBowesRestApi\Api\Data\ParcelDtoInterface;
use Labelin\PitneyBowesRestApi\Api\ShipmentInterface;
use Labelin\PitneyBowesRestApi\Model\Api\Data\ShipmentsRatesDto;
use Labelin\PitneyBowesShipping\Helper\Config\FreeShippingConfig as ConfigHelper;

class Shipment implements ShipmentInterface
{
    /** @var ConfigHelper */
    protected $configHelper;

    /** @var OauthConfiguration */
    protected $oauthConfiguration;

    public function __construct(
        ConfigHelper $configHelper,
        OauthConfiguration $oauthConfiguration
    ) {
        $this->configHelper = $configHelper;
        $this->oauthConfiguration = $oauthConfiguration;
    }

    /**
     * @param AddressDtoInterface $fromAddress
     * @param AddressDtoInterface $toAddress
     * @param ParcelDtoInterface $parcel
     * @param ShipmentsRatesDto $rates
     * @param $x_pb_transaction_id
     * @return Errors|ShipmentApiResponse
     *
     * @throws ApiException
     */
    public function requestShipmentLabel(
        AddressDtoInterface $fromAddress,
        AddressDtoInterface $toAddress,
        ParcelDtoInterface $parcel,
        ShipmentsRatesDto $rates,
        $x_pb_transaction_id
    ) {
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
                    'serviceId' => $rates->getServiceId(),
                    'induction_postal_code' => $rates->getInductionPostalCode(),
                ]),
            ],
            'shipment_options' => [
                new Parameter([
                    'name' => 'SHIPPER_ID',
                    'value' => $this->configHelper->getMerchantId(),
                ]),
            ],
        ]);

        $this->oauthConfiguration->setAccessToken($this->configHelper->getApiAccessToken());

        return (new ShipmentApi($this->oauthConfiguration))->createShipmentLabel($x_pb_transaction_id, $shipment);
    }
}
