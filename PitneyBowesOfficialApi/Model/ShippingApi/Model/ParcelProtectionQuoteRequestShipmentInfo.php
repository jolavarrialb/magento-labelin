<?php
/**
 * ParcelProtectionQuoteRequestShipmentInfo
 *
 * PHP version 5
 *
 * @category Class
 * @package  pitneybowesShipping
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */

/**
 * Shipping API
 *
 * Shipping API Sample.
 *
 * The version of the OpenAPI document: 1.0.0
 * Contact: support@pb.com
 * Generated by: https://openapi-generator.tech
 * OpenAPI Generator version: 4.3.1
 */

/**
 * NOTE: This class is auto generated by OpenAPI Generator (https://openapi-generator.tech).
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */

namespace Labelin\PitneyBowesOfficialApi\Model\ShippingApi\Model;

use \ArrayAccess;
use \Labelin\PitneyBowesOfficialApi\Model\ObjectSerializer;

/**
 * ParcelProtectionQuoteRequestShipmentInfo Class Doc Comment
 *
 * @category Class
 * @package  pitneybowesShipping
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */
class ParcelProtectionQuoteRequestShipmentInfo implements ModelInterface, ArrayAccess
{
    const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'ParcelProtectionQuoteRequest_shipmentInfo';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'carrier' => 'string',
        'service_id' => 'string',
        'insurance_coverage_value' => 'int',
        'insurance_coverage_value_currency' => 'string',
        'parcel_info' => '\pitneybowesShipping\shippingApi\model\ParcelProtectionQuoteRequestShipmentInfoParcelInfo',
        'shipper_info' => '\pitneybowesShipping\shippingApi\model\ParcelProtectionQuoteRequestShipmentInfoShipperInfo',
        'consignee_info' => '\pitneybowesShipping\shippingApi\model\ParcelProtectionQuoteRequestShipmentInfoConsigneeInfo'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPIFormats = [
        'carrier' => null,
        'service_id' => null,
        'insurance_coverage_value' => null,
        'insurance_coverage_value_currency' => null,
        'parcel_info' => null,
        'shipper_info' => null,
        'consignee_info' => null
    ];

    /**
     * Array of property to type mappings. Used for (de)serialization
     *
     * @return array
     */
    public static function openAPITypes()
    {
        return self::$openAPITypes;
    }

    /**
     * Array of property to format mappings. Used for (de)serialization
     *
     * @return array
     */
    public static function openAPIFormats()
    {
        return self::$openAPIFormats;
    }

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'carrier' => 'carrier',
        'service_id' => 'serviceId',
        'insurance_coverage_value' => 'insuranceCoverageValue',
        'insurance_coverage_value_currency' => 'insuranceCoverageValueCurrency',
        'parcel_info' => 'parcelInfo',
        'shipper_info' => 'shipperInfo',
        'consignee_info' => 'consigneeInfo'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'carrier' => 'setCarrier',
        'service_id' => 'setServiceId',
        'insurance_coverage_value' => 'setInsuranceCoverageValue',
        'insurance_coverage_value_currency' => 'setInsuranceCoverageValueCurrency',
        'parcel_info' => 'setParcelInfo',
        'shipper_info' => 'setShipperInfo',
        'consignee_info' => 'setConsigneeInfo'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'carrier' => 'getCarrier',
        'service_id' => 'getServiceId',
        'insurance_coverage_value' => 'getInsuranceCoverageValue',
        'insurance_coverage_value_currency' => 'getInsuranceCoverageValueCurrency',
        'parcel_info' => 'getParcelInfo',
        'shipper_info' => 'getShipperInfo',
        'consignee_info' => 'getConsigneeInfo'
    ];

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @return array
     */
    public static function attributeMap()
    {
        return self::$attributeMap;
    }

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @return array
     */
    public static function setters()
    {
        return self::$setters;
    }

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @return array
     */
    public static function getters()
    {
        return self::$getters;
    }

    /**
     * The original name of the model.
     *
     * @return string
     */
    public function getModelName()
    {
        return self::$openAPIModelName;
    }





    /**
     * Associative array for storing property values
     *
     * @var mixed[]
     */
    protected $container = [];

    /**
     * Constructor
     *
     * @param mixed[] $data Associated array of property values
     *                      initializing the model
     */
    public function __construct(array $data = null)
    {
        $this->container['carrier'] = isset($data['carrier']) ? $data['carrier'] : null;
        $this->container['service_id'] = isset($data['service_id']) ? $data['service_id'] : null;
        $this->container['insurance_coverage_value'] = isset($data['insurance_coverage_value']) ? $data['insurance_coverage_value'] : null;
        $this->container['insurance_coverage_value_currency'] = isset($data['insurance_coverage_value_currency']) ? $data['insurance_coverage_value_currency'] : null;
        $this->container['parcel_info'] = isset($data['parcel_info']) ? $data['parcel_info'] : null;
        $this->container['shipper_info'] = isset($data['shipper_info']) ? $data['shipper_info'] : null;
        $this->container['consignee_info'] = isset($data['consignee_info']) ? $data['consignee_info'] : null;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];

        if ($this->container['carrier'] === null) {
            $invalidProperties[] = "'carrier' can't be null";
        }
        if ($this->container['service_id'] === null) {
            $invalidProperties[] = "'service_id' can't be null";
        }
        if ($this->container['insurance_coverage_value'] === null) {
            $invalidProperties[] = "'insurance_coverage_value' can't be null";
        }
        if ($this->container['insurance_coverage_value_currency'] === null) {
            $invalidProperties[] = "'insurance_coverage_value_currency' can't be null";
        }
        if ($this->container['parcel_info'] === null) {
            $invalidProperties[] = "'parcel_info' can't be null";
        }
        if ($this->container['shipper_info'] === null) {
            $invalidProperties[] = "'shipper_info' can't be null";
        }
        if ($this->container['consignee_info'] === null) {
            $invalidProperties[] = "'consignee_info' can't be null";
        }
        return $invalidProperties;
    }

    /**
     * Validate all the properties in the model
     * return true if all passed
     *
     * @return bool True if all properties are valid
     */
    public function valid()
    {
        return count($this->listInvalidProperties()) === 0;
    }


    /**
     * Gets carrier
     *
     * @return string
     */
    public function getCarrier()
    {
        return $this->container['carrier'];
    }

    /**
     * Sets carrier
     *
     * @param string $carrier carrier
     *
     * @return $this
     */
    public function setCarrier($carrier)
    {
        $this->container['carrier'] = $carrier;

        return $this;
    }

    /**
     * Gets service_id
     *
     * @return string
     */
    public function getServiceId()
    {
        return $this->container['service_id'];
    }

    /**
     * Sets service_id
     *
     * @param string $service_id service_id
     *
     * @return $this
     */
    public function setServiceId($service_id)
    {
        $this->container['service_id'] = $service_id;

        return $this;
    }

    /**
     * Gets insurance_coverage_value
     *
     * @return int
     */
    public function getInsuranceCoverageValue()
    {
        return $this->container['insurance_coverage_value'];
    }

    /**
     * Sets insurance_coverage_value
     *
     * @param int $insurance_coverage_value insurance_coverage_value
     *
     * @return $this
     */
    public function setInsuranceCoverageValue($insurance_coverage_value)
    {
        $this->container['insurance_coverage_value'] = $insurance_coverage_value;

        return $this;
    }

    /**
     * Gets insurance_coverage_value_currency
     *
     * @return string
     */
    public function getInsuranceCoverageValueCurrency()
    {
        return $this->container['insurance_coverage_value_currency'];
    }

    /**
     * Sets insurance_coverage_value_currency
     *
     * @param string $insurance_coverage_value_currency insurance_coverage_value_currency
     *
     * @return $this
     */
    public function setInsuranceCoverageValueCurrency($insurance_coverage_value_currency)
    {
        $this->container['insurance_coverage_value_currency'] = $insurance_coverage_value_currency;

        return $this;
    }

    /**
     * Gets parcel_info
     *
     * @return \pitneybowesShipping\shippingApi\model\ParcelProtectionQuoteRequestShipmentInfoParcelInfo
     */
    public function getParcelInfo()
    {
        return $this->container['parcel_info'];
    }

    /**
     * Sets parcel_info
     *
     * @param \pitneybowesShipping\shippingApi\model\ParcelProtectionQuoteRequestShipmentInfoParcelInfo $parcel_info parcel_info
     *
     * @return $this
     */
    public function setParcelInfo($parcel_info)
    {
        $this->container['parcel_info'] = $parcel_info;

        return $this;
    }

    /**
     * Gets shipper_info
     *
     * @return \pitneybowesShipping\shippingApi\model\ParcelProtectionQuoteRequestShipmentInfoShipperInfo
     */
    public function getShipperInfo()
    {
        return $this->container['shipper_info'];
    }

    /**
     * Sets shipper_info
     *
     * @param \pitneybowesShipping\shippingApi\model\ParcelProtectionQuoteRequestShipmentInfoShipperInfo $shipper_info shipper_info
     *
     * @return $this
     */
    public function setShipperInfo($shipper_info)
    {
        $this->container['shipper_info'] = $shipper_info;

        return $this;
    }

    /**
     * Gets consignee_info
     *
     * @return \pitneybowesShipping\shippingApi\model\ParcelProtectionQuoteRequestShipmentInfoConsigneeInfo
     */
    public function getConsigneeInfo()
    {
        return $this->container['consignee_info'];
    }

    /**
     * Sets consignee_info
     *
     * @param \pitneybowesShipping\shippingApi\model\ParcelProtectionQuoteRequestShipmentInfoConsigneeInfo $consignee_info consignee_info
     *
     * @return $this
     */
    public function setConsigneeInfo($consignee_info)
    {
        $this->container['consignee_info'] = $consignee_info;

        return $this;
    }
    /**
     * Returns true if offset exists. False otherwise.
     *
     * @param integer $offset Offset
     *
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return isset($this->container[$offset]);
    }

    /**
     * Gets offset.
     *
     * @param integer $offset Offset
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }

    /**
     * Sets value based on offset.
     *
     * @param integer $offset Offset
     * @param mixed   $value  Value to be set
     *
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    /**
     * Unsets offset.
     *
     * @param integer $offset Offset
     *
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->container[$offset]);
    }

    /**
     * Gets the string presentation of the object
     *
     * @return string
     */
    public function __toString()
    {
        return json_encode(
            ObjectSerializer::sanitizeForSerialization($this),
            JSON_PRETTY_PRINT
        );
    }

    /**
     * Gets a header-safe presentation of the object
     *
     * @return string
     */
    public function toHeaderValue()
    {
        return json_encode(ObjectSerializer::sanitizeForSerialization($this));
    }
}

