<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesRestApi\Model\Api;

use Labelin\PitneyBowesOfficialApi\Model\Configuration as OauthConfiguration;
use Labelin\PitneyBowesOfficialApi\Model\Shipping\RateParcelsApi;
use Labelin\PitneyBowesOfficialApi\Model\Api\Model\Address;
use Labelin\PitneyBowesOfficialApi\Model\Api\Model\Parameter;
use Labelin\PitneyBowesOfficialApi\Model\Api\Model\Parcel;
use Labelin\PitneyBowesOfficialApi\Model\Api\Model\ParcelDimension;
use Labelin\PitneyBowesOfficialApi\Model\Api\Model\ParcelWeight;
use Labelin\PitneyBowesOfficialApi\Model\Api\Model\Rate;
use Labelin\PitneyBowesOfficialApi\Model\Api\Model\Shipment;
use Labelin\PitneyBowesRestApi\Api\Data\AddressDtoInterface;
use Labelin\PitneyBowesRestApi\Api\Data\ParcelDtoInterface;
use Labelin\PitneyBowesRestApi\Api\RatesInterface;
use Labelin\PitneyBowesRestApi\Model\Api\Data\RateDto;
use Labelin\PitneyBowesShipping\Helper\Config\FreeShippingConfig as ConfigHelper;
use Psr\Log\LoggerInterface;

class Rates implements RatesInterface
{
    protected const URI = '/shippingservices/v1/rates';

    /** @var ConfigHelper */
    protected $configHelper;

    /** @var OauthConfiguration */
    protected $oauthConfiguration;

    /** @var LoggerInterface */
    protected $logger;

    /** @var array */
    protected $rates = [];

    public function __construct(
        ConfigHelper $configHelper,
        OauthConfiguration $oauthConfiguration,
        LoggerInterface $logger
    ) {
        $this->configHelper = $configHelper;
        $this->oauthConfiguration = $oauthConfiguration;
        $this->logger = $logger;
    }

    /**
     * @param AddressDtoInterface $fromAddress
     * @param AddressDtoInterface $toAddress
     * @param ParcelDtoInterface  $parcel
     *
     * @return \Labelin\PitneyBowesRestApi\Api\Data\RateDtoInterface[]
     */
    public function requestRates(
        AddressDtoInterface $fromAddress,
        AddressDtoInterface $toAddress,
        ParcelDtoInterface $parcel
    ) {
        $shipment = new Shipment([
            'from_address' => new Address($fromAddress->toArray()),
            'to_address' => new Address($toAddress->toArray()),
            'parcel' => new Parcel([
                'weight' => new ParcelWeight($parcel->toWeightArray()),
                'dimension' => new ParcelDimension($parcel->toDimensionsArray()),
            ]),
            'rates' => [
                new Rate([
                    'carrier' => current($this->configHelper->getAllowedMethods()),
                    'parcel_type' => $this->configHelper->getContainer(),
                    'induction_postal_code' => $fromAddress->getPostcode(),
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

        $this->logger->info('REQUEST:');
        $this->logger->info($shipment);

        try {
            $response = (new RateParcelsApi($this->oauthConfiguration))->rateParcel($shipment);
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());

            return [];
        }

        $this->logger->info('RESPONSE:');
        $this->logger->info($response);

        foreach ($response->getRates() as $rate) {
            $this->rates[] = (new RateDto())
                ->setServiceId((string)$rate->getServiceId())
                ->setBaseCharge($rate->getBaseCharge())
                ->setRateTypeId($rate->getRateTypeId())
                ->setTotalCarrierCharge($rate->getTotalCarrierCharge());
        }

        return $this->rates;
    }
}
