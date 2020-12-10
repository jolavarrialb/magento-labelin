<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesShipping\Model\Carrier;

use Labelin\PitneyBowesRestApi\Model\Api\Data\AddressDto;
use Labelin\PitneyBowesRestApi\Model\Api\Data\ParcelDto;
use Labelin\PitneyBowesRestApi\Model\Api\Data\ShipmentsRatesDto;
use Labelin\PitneyBowesRestApi\Model\Api\Data\SpecialServiceDto;
use Labelin\PitneyBowesRestApi\Model\Api\Shipment;
use Labelin\PitneyBowesShipping\Helper\Address;
use Labelin\PitneyBowesShipping\Helper\Config\FreeShippingConfig as ConfigHelper;
use Labelin\PitneyBowesRestApi\Api\CancelShipmentInterface;
use Labelin\PitneyBowesRestApi\Api\Data\VerifiedAddressDtoInterface;
use Labelin\PitneyBowesRestApi\Model\Api\VerifyAddress;
use Labelin\PitneyBowesShipping\Helper\GeneralConfig;
use Magento\CatalogInventory\Api\StockRegistryInterface;
use Magento\Checkout\Model\Session;
use Magento\Directory\Helper\Data;
use Magento\Directory\Model\CountryFactory;
use Magento\Directory\Model\CurrencyFactory;
use Magento\Directory\Model\RegionFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Xml\Security;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory;
use Magento\Quote\Model\Quote\Address\RateResult\MethodFactory;
use Magento\Shipping\Model\Carrier\AbstractCarrierOnline;
use Magento\Shipping\Model\Carrier\CarrierInterface;
use Magento\Shipping\Model\Rate\ResultFactory;
use Magento\Shipping\Model\Simplexml\ElementFactory;
use Magento\Shipping\Model\Tracking\Result\ErrorFactory as TrackingErrorFactory;
use Magento\Shipping\Model\Tracking\Result\StatusFactory;
use Magento\Shipping\Model\Tracking\ResultFactory as TrackingResultFactory;
use Psr\Log\LoggerInterface;

abstract class AbstractPitneyBowesCarrier extends AbstractCarrierOnline implements CarrierInterface
{
    public const TRACKING_URL = 'https://tracking.pb.com/';

    /** @var MethodFactory */
    protected $rateMethodFactory;

    /** @var GeneralConfig */
    protected $carrierConfig;

    /** @var Shipment */
    protected $shipment;

    /** @var Address */
    protected $addressHelper;

    /** @var ConfigHelper */
    protected $configHelper;

    /** @var VerifyAddress */
    protected $addressVerifier;

    /** @var Session */
    protected $checkoutSession;

    /** @var CancelShipmentInterface */
    protected $cancelShipmentRestApi;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        ErrorFactory $rateErrorFactory,
        LoggerInterface $logger,
        Security $xmlSecurity,
        ElementFactory $xmlElFactory,
        ResultFactory $rateFactory,
        MethodFactory $rateMethodFactory,
        TrackingResultFactory $trackFactory,
        TrackingErrorFactory $trackErrorFactory,
        StatusFactory $trackStatusFactory,
        RegionFactory $regionFactory,
        CountryFactory $countryFactory,
        CurrencyFactory $currencyFactory,
        Data $directoryData,
        StockRegistryInterface $stockRegistry,
        GeneralConfig $carrierConfig,
        VerifyAddress $addressVerifier,
        Session $checkoutSession,
        CancelShipmentInterface $cancelShipmentRestApi,
        Shipment $shipment,
        ConfigHelper $configHelper,
        Address $addressHelper,
        array $data = []
    ) {
        parent::__construct(
            $scopeConfig,
            $rateErrorFactory,
            $logger,
            $xmlSecurity,
            $xmlElFactory,
            $rateFactory,
            $rateMethodFactory,
            $trackFactory,
            $trackErrorFactory,
            $trackStatusFactory,
            $regionFactory,
            $countryFactory,
            $currencyFactory,
            $directoryData,
            $stockRegistry,
            $data
        );

        $this->rateMethodFactory = $rateMethodFactory;
        $this->carrierConfig = $carrierConfig;

        $this->shipment = $shipment;

        $this->addressHelper = $addressHelper;
        $this->configHelper = $configHelper;

        $this->addressVerifier = $addressVerifier;
        $this->checkoutSession = $checkoutSession;

        $this->cancelShipmentRestApi = $cancelShipmentRestApi;
    }

    /**
     * @inheritDoc
     */
    public function collectRates(RateRequest $request)
    {
        $method = $this->rateMethodFactory->create();

        $method->setCarrier($this->_code);
        $method->setCarrierTitle($this->getConfigData('title'));

        $method->setMethod($this->_code);
        $method->setMethodTitle($this->getConfigData('name'));

        $method->setPrice($this->getConfigData('shipping_cost'));
        $method->setCost($this->getConfigData('shipping_cost'));

        return $method;
    }

    /**
     * @inheritDoc
     */
    public function requestToShipment($request)
    {
        $packages = $request->getPackages();

        if (!is_array($packages) || !$packages) {
            throw new LocalizedException(__('No packages for request'));
        }

        if ($request->getStoreId() !== null) {
            $this->setStore($request->getStoreId());
        }

        $data = [];
        $response = new DataObject([]);

        $isServicesExists = true;

        foreach ($packages as $package) {
            if (!$package['params']['service']) {
                $isServicesExists = false;

                break;
            }
        }

        if (!$isServicesExists) {
            $response->setErrors(__('One of your services is empty. Please request rates for package.'));

            return $response;
        }

        foreach ($packages as $packageId => $package) {
            $request->setPackageId($packageId);
            $request->setPackageParams(new DataObject($package['params']));
            $request->setPackageItems($package['items']);
            $result = $this->_doShipmentRequest($request);

            if ($result->hasErrors()) {
                $this->rollBack($data);
                break;
            } else {
                $data[] = [
                    'tracking_number' => $result->getTrackingNumber(),
                    'label_content' => $result->getShippingLabelContent(),
                ];
            }

            if (!isset($isFirstRequest)) {
                $request->setMasterTrackingId($result->getTrackingNumber());
                $isFirstRequest = false;
            }
        }

        if ($result->getErrors()) {
            $response->setErrors($result->getErrors());
        }

        $response->setData('info', $data);

        return $response;
    }

    public function getContainerTypes(DataObject $params = null): array
    {
        return $this->carrierConfig->getCode('container_description');
    }

    public function getWeightUnitOfMeasure(): array
    {
        return $this->carrierConfig->getCode('unit_of_measure');
    }

    public function getAllowedMethods(): array
    {
        return [
            $this->_code => $this->getConfigData('name'),
        ];
    }

    /**
     * @param DataObject $request
     *
     * @return $this|bool|AbstractPitneyBowesCarrier|DataObject
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function processAdditionalValidation(DataObject $request)
    {
        $quoteShippingAddress = $this->checkoutSession->getQuote()->getShippingAddress();

        $address = (new AddressDto())
            ->setAddressLines($quoteShippingAddress->getStreet())
            ->setCity($request->getDestCity())
            ->setState($request->getDestRegionCode())
            ->setPostcode($request->getDestPostcode())
            ->setCountry($request->getDestCountryId());

        $response = $this->addressVerifier->verifyAddress($address);

        if ($response instanceof VerifiedAddressDtoInterface && $response->isValid()) {
            $quoteShippingAddress
                ->setPostcode($response->getPostcode())
                ->setStreet($response->getAddressLines());

            return $this;
        }

        if ($this->getConfigData('showmethod')) {
            $error = $this->_rateErrorFactory->create();
            $error->setCarrier($this->_code);
            $error->setCarrierTitle($this->getConfigData('title'));
            $error->setErrorMessage($response);

            return $error;
        }

        return false;
    }

    protected function _doShipmentRequest(DataObject $request)
    {
        $this->_prepareShipmentRequest($request);
        $packageParams = $request->getPackageParams();
        $fromAddress = $this->addressHelper->getAddressDtoModel($packageParams->getData('fromAddress'));
        $toAddress = $this->addressHelper->getAddressDtoModel($packageParams->getData('toAddress'));

        $parcel = (new ParcelDto())
            ->setHeight($packageParams->getHeight())
            ->setLength($packageParams->getLength())
            ->setWidth($packageParams->getWidth())
            ->setDimensionsUnitOfMeasurement($packageParams->getDimensionUnits())
            ->setWeight($packageParams->getWeight())
            ->setWeightUnitOfMeasurement($packageParams->getWeightUnits());

        $rates = (new ShipmentsRatesDto())
            ->setServiceId($packageParams->getService())
            ->setCarrier(current($this->configHelper->getAllowedMethods()))
            ->setParcelType($this->configHelper->getContainer())
            ->setInductionPostalCode($fromAddress->getPostcode());

        return $this->shipment->requestShipmentLabel($fromAddress, $toAddress, $parcel, $rates, $request);
    }
}
