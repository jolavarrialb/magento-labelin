<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesShipping\Model\Carrier;

use Labelin\PitneyBowesShipping\Helper\GeneralConfig;
use Magento\CatalogInventory\Api\StockRegistryInterface;
use Magento\Directory\Helper\Data;
use Magento\Directory\Model\CountryFactory;
use Magento\Directory\Model\CurrencyFactory;
use Magento\Directory\Model\RegionFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\DataObject;
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
    /** @var MethodFactory */
    protected $rateMethodFactory;

    /** @var GeneralConfig */
    protected $carrierConfig;

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

    public function requestToShipment($request): DataObject
    {
        return new DataObject(['info' => []]);
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

    protected function _doShipmentRequest(DataObject $request): array
    {
        return [];
    }
}
