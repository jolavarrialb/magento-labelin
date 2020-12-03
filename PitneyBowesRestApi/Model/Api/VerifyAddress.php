<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesRestApi\Model\Api;

use Labelin\PitneyBowesOfficialApi\Model\Api\Model\Address;
use Labelin\PitneyBowesOfficialApi\Model\Configuration as OauthConfiguration;
use Labelin\PitneyBowesOfficialApi\Model\Shipping\AddressValidationApi;
use Labelin\PitneyBowesRestApi\Api\Data;
use Labelin\PitneyBowesRestApi\Api\Data\AddressDtoInterface;
use Labelin\PitneyBowesRestApi\Api\VerifyAddressInterface;
use Labelin\PitneyBowesRestApi\Model\Api\Data\VerifiedAddressDto;
use Labelin\PitneyBowesShipping\Helper\Config\FreeShippingConfig as ConfigHelper;
use Psr\Log\LoggerInterface;

class VerifyAddress implements VerifyAddressInterface
{
    protected const URI = '/shippingservices/v1/addresses/verify';

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

    public function verifyAddress(AddressDtoInterface $address)
    {
        $addressApi = new Address($address->toShippingOptionsArray());

        $this->oauthConfiguration->setAccessToken($this->configHelper->getApiAccessToken());

        $this->logger->info('REQUEST:');
        $this->logger->info(json_encode($address->toShippingOptionsArray()));

        try {
            $response = (new AddressValidationApi($this->oauthConfiguration))
                ->verifyAddress($addressApi, true, false);
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
            $error = json_decode($exception->getResponseBody(), true);
            $error = current($error['errors']);

            return $error['errorDescription'] . '. ' . $error['additionalInfo'];
        }

        $this->logger->info('RESPONSE:');
        $this->logger->info($response->getStatus());

        return (new VerifiedAddressDto())
            ->setCompany($response->getCompany())
            ->setName($response->getName())
            ->setPhone($response->getPhone())
            ->setEmail($response->getEmail())
            ->setAddressLines($response->getAddressLines())
            ->setCity($response->getCityTown())
            ->setState($response->getStateProvince())
            ->setPostcode($response->getPostalCode())
            ->setCountry($response->getCountryCode())
            ->setDeliveryPoint($response->getDeliveryPoint())
            ->setCarrierRoute($response->getCarrierRoute())
            ->setStatus($response->getStatus());
    }
}
