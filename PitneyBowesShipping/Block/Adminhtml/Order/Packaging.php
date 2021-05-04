<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesShipping\Block\Adminhtml\Order;

use Labelin\PitneyBowesRestApi\Model\Api\Data\AddressDto;
use Magento\Backend\Block\Template\Context;
use Magento\Directory\Model\Region;
use Magento\Directory\Model\RegionFactory;
use Magento\Framework\DataObject;
use Magento\Framework\Json\EncoderInterface;
use Magento\Framework\Registry;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Shipping\Block\Adminhtml\Order\Packaging as MagentoPackaging;
use Magento\Shipping\Model\Carrier\Source\GenericInterface;
use Magento\Shipping\Model\CarrierFactory;
use Magento\Store\Model\Information as StoreInformation;
use Labelin\PitneyBowesShipping\Helper\GeneralConfig;

class Packaging extends MagentoPackaging
{
    /** @var StoreInformation */
    protected $storeInformation;

    /** @var RegionFactory */
    protected $regionFactory;

    /** @var Json  */
    protected $jsonSerializer;

    /** @var GeneralConfig  */
    protected $generalConfig;

    public function __construct(
        Context $context,
        EncoderInterface $jsonEncoder,
        GenericInterface $sourceSizeModel,
        Registry $coreRegistry,
        CarrierFactory $carrierFactory,
        StoreInformation $storeInformation,
        RegionFactory $regionFactory,
        Json $jsonSerializer,
        GeneralConfig $generalConfig,
        array $data = []
    ) {
        parent::__construct($context, $jsonEncoder, $sourceSizeModel, $coreRegistry, $carrierFactory, $data);

        $this->storeInformation = $storeInformation;
        $this->regionFactory = $regionFactory;
        $this->jsonSerializer = $jsonSerializer;
        $this->generalConfig = $generalConfig;
    }

    public function getFromAddressJson(): string
    {
        $store = $this->getStoreInformation();

        $address = (new AddressDto())
            ->setCompany($store->getData('name'))
            ->setName($store->getData('name'))
            ->setPhone($store->getData('phone'))
            ->setEmail($this->getShipment()->getStore()->getConfig('trans_email/ident_general/email'))
            ->setAddressLines([$store->getData('street_line1'), $store->getData('street_line2'),])
            ->setCity($store->getData('city'))
            ->setState($this->iniRegion()->load($store->getData('region_id'))->getCode())
            ->setPostcode($store->getData('postcode'))
            ->setCountry($store->getData('country_id'));

        return $this->_jsonEncoder->encode($address);
    }

    public function getToAddressJson(): string
    {
        $shippingAddress = $this->getShipment()->getShippingAddress();

        $address = (new AddressDto())
            ->setCompany($shippingAddress->getCompany() ?? '')
            ->setName($shippingAddress->getName())
            ->setPhone($shippingAddress->getTelephone() ?? '')
            ->setEmail($shippingAddress->getEmail())
            ->setAddressLines($shippingAddress->getStreet())
            ->setCity($shippingAddress->getCity())
            ->setState($shippingAddress->getRegionCode() ?? '')
            ->setPostcode($shippingAddress->getPostcode())
            ->setCountry($shippingAddress->getCountryId());

        return $this->_jsonEncoder->encode($address);
    }

    protected function getStoreInformation(): DataObject
    {
        return $this->storeInformation->getStoreInformationObject($this->getShipment()->getStore());
    }

    protected function iniRegion(array $data = []): Region
    {
        return $this->regionFactory->create($data);
    }

    public function getConfigDataJson() {
        $data = $this->jsonSerializer->unserialize(parent::getConfigDataJson());

        $data['packagesTypes'] = $this->generalConfig->getCode('packagesTypes');

        return $this->jsonSerializer->serialize($data);
    }
}
