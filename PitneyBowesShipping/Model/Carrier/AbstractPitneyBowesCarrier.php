<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesShipping\Model\Carrier;

use Labelin\PitneyBowesRestApi\Api\CancelShipmentInterface;
use Labelin\PitneyBowesRestApi\Api\Data\VerifiedAddressDtoInterface;
use Labelin\PitneyBowesRestApi\Model\Api\Data\AddressDto;
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
use Magento\Shipping\Model\Shipment\Request;
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

    public function requestToShipment($request)
    {
        $packages = $request->getPackages();
        if (!is_array($packages) || !$packages) {
            throw new LocalizedException(__('No packages for request'));
        }
        if ($request->getStoreId() != null) {
            $this->setStore($request->getStoreId());
        }
        $data = [];
        foreach ($packages as $packageId => $package) {
            $request->setPackageId($packageId);
            $request->setPackagingType($package['params']['container']);
            $request->setPackageWeight($package['params']['weight']);
            $request->setPackageParams(new \Magento\Framework\DataObject($package['params']));
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

        $response = new \Magento\Framework\DataObject(['info' => $data]);
        if ($result->getErrors()) {
            $response->setErrors($result->getErrors());
        }

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

    /**
     * @inheritDoc
     */
    public function returnOfShipment($request)
    {
        $response = $this->cancelShipmentRestApi->cancelShipment((int)$request->getOrderShipment()->getId());

        if (!$response) {
            return new DataObject([]);
        }

        return $response;
    }

    protected function _doShipmentRequest(\Magento\Framework\DataObject $request)
    {
        $this->_prepareShipmentRequest($request);
        $result = new \Magento\Framework\DataObject();
        $service = $this->getCode('service_to_code', $request->getShippingMethod());
        $recipientUSCountry = $this->_isUSCountry($request->getRecipientAddressCountryCode());

        if ($recipientUSCountry && $service == 'Priority Express') {
            $requestXml = $this->_formUsExpressShipmentRequest($request);
            $api = 'ExpressMailLabel';
        } else {
            if ($recipientUSCountry) {
                $requestXml = $this->_formUsSignatureConfirmationShipmentRequest($request, $service);
                if ($this->getConfigData('mode')) {
                    $api = 'SignatureConfirmationV3';
                } else {
                    $api = 'SignatureConfirmationCertifyV3';
                }
            } else {
                if ($service == 'First Class') {
                    $requestXml = $this->_formIntlShipmentRequest($request);
                    $api = 'FirstClassMailIntl';
                } else {
                    if ($service == 'Priority') {
                        $requestXml = $this->_formIntlShipmentRequest($request);
                        $api = 'PriorityMailIntl';
                    } else {
                        $requestXml = $this->_formIntlShipmentRequest($request);
                        $api = 'ExpressMailIntl';
                    }
                }
            }
        }

        $debugData = ['request' => $this->filterDebugData($requestXml)];
        $url = $this->getConfigData('gateway_secure_url');
        if (!$url) {
            $url = $this->_defaultGatewayUrl;
        }
        $client = $this->_httpClientFactory->create();
        $client->setUri($url);
        $client->setConfig(['maxredirects' => 0, 'timeout' => 30]);
        $client->setParameterGet('API', $api);
        $client->setParameterGet('XML', $requestXml);
        $response = $client->request()->getBody();

        $response = $this->parseXml($response);

        if ($response !== false) {
            if ($response->getName() == 'Error') {
                $debugData['result'] = [
                    'error' => $response->Description,
                    'code' => $response->Number,
                    'xml' => $response->asXML(),
                ];
                $this->_debug($debugData);
                $result->setErrors($debugData['result']['error']);
            } else {
                if ($recipientUSCountry && $service == 'Priority Express') {
                    // phpcs:ignore Magento2.Functions.DiscouragedFunction
                    $labelContent = base64_decode((string)$response->EMLabel);
                    $trackingNumber = (string)$response->EMConfirmationNumber;
                } elseif ($recipientUSCountry) {
                    // phpcs:ignore Magento2.Functions.DiscouragedFunction
                    $labelContent = base64_decode((string)$response->SignatureConfirmationLabel);
                    $trackingNumber = (string)$response->SignatureConfirmationNumber;
                } else {
                    // phpcs:ignore Magento2.Functions.DiscouragedFunction
                    $labelContent = base64_decode((string)$response->LabelImage);
                    $trackingNumber = (string)$response->BarcodeNumber;
                }
                $result->setShippingLabelContent($labelContent);
                $result->setTrackingNumber($trackingNumber);
            }
        }

        $result->setGatewayResponse($response);
        $debugData['result'] = $response;
        $this->_debug($debugData);

        return $result;
    }
}
