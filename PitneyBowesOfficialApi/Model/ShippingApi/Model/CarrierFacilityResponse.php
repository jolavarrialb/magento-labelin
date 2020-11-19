<?php
/**
 * CarrierFacilityResponse
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
 * CarrierFacilityResponse Class Doc Comment
 *
 * @category Class
 * @package  pitneybowesShipping
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */
class CarrierFacilityResponse implements ModelInterface, ArrayAccess
{
    const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'CarrierFacilityResponse';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'address' => '\pitneybowesShipping\shippingApi\model\Address',
        'carrier' => 'string',
        'carrier_facility_options' => '\pitneybowesShipping\shippingApi\model\CarrierFacilityResponseCarrierFacilityOptions[]',
        'carrier_facility_suggestions' => '\pitneybowesShipping\shippingApi\model\CarrierFacilityResponseCarrierFacilitySuggestions[]'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPIFormats = [
        'address' => null,
        'carrier' => null,
        'carrier_facility_options' => null,
        'carrier_facility_suggestions' => null
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
        'address' => 'address',
        'carrier' => 'carrier',
        'carrier_facility_options' => 'carrierFacilityOptions',
        'carrier_facility_suggestions' => 'carrierFacilitySuggestions'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'address' => 'setAddress',
        'carrier' => 'setCarrier',
        'carrier_facility_options' => 'setCarrierFacilityOptions',
        'carrier_facility_suggestions' => 'setCarrierFacilitySuggestions'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'address' => 'getAddress',
        'carrier' => 'getCarrier',
        'carrier_facility_options' => 'getCarrierFacilityOptions',
        'carrier_facility_suggestions' => 'getCarrierFacilitySuggestions'
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
        $this->container['address'] = isset($data['address']) ? $data['address'] : null;
        $this->container['carrier'] = isset($data['carrier']) ? $data['carrier'] : null;
        $this->container['carrier_facility_options'] = isset($data['carrier_facility_options']) ? $data['carrier_facility_options'] : null;
        $this->container['carrier_facility_suggestions'] = isset($data['carrier_facility_suggestions']) ? $data['carrier_facility_suggestions'] : null;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];

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
     * Gets address
     *
     * @return \pitneybowesShipping\shippingApi\model\Address|null
     */
    public function getAddress()
    {
        return $this->container['address'];
    }

    /**
     * Sets address
     *
     * @param \pitneybowesShipping\shippingApi\model\Address|null $address address
     *
     * @return $this
     */
    public function setAddress($address)
    {
        $this->container['address'] = $address;

        return $this;
    }

    /**
     * Gets carrier
     *
     * @return string|null
     */
    public function getCarrier()
    {
        return $this->container['carrier'];
    }

    /**
     * Sets carrier
     *
     * @param string|null $carrier carrier
     *
     * @return $this
     */
    public function setCarrier($carrier)
    {
        $this->container['carrier'] = $carrier;

        return $this;
    }

    /**
     * Gets carrier_facility_options
     *
     * @return \pitneybowesShipping\shippingApi\model\CarrierFacilityResponseCarrierFacilityOptions[]|null
     */
    public function getCarrierFacilityOptions()
    {
        return $this->container['carrier_facility_options'];
    }

    /**
     * Sets carrier_facility_options
     *
     * @param \pitneybowesShipping\shippingApi\model\CarrierFacilityResponseCarrierFacilityOptions[]|null $carrier_facility_options carrier_facility_options
     *
     * @return $this
     */
    public function setCarrierFacilityOptions($carrier_facility_options)
    {
        $this->container['carrier_facility_options'] = $carrier_facility_options;

        return $this;
    }

    /**
     * Gets carrier_facility_suggestions
     *
     * @return \pitneybowesShipping\shippingApi\model\CarrierFacilityResponseCarrierFacilitySuggestions[]|null
     */
    public function getCarrierFacilitySuggestions()
    {
        return $this->container['carrier_facility_suggestions'];
    }

    /**
     * Sets carrier_facility_suggestions
     *
     * @param \pitneybowesShipping\shippingApi\model\CarrierFacilityResponseCarrierFacilitySuggestions[]|null $carrier_facility_suggestions carrier_facility_suggestions
     *
     * @return $this
     */
    public function setCarrierFacilitySuggestions($carrier_facility_suggestions)
    {
        $this->container['carrier_facility_suggestions'] = $carrier_facility_suggestions;

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

