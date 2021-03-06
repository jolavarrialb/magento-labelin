<?php
/**
 * CrossBorderQuotesResponseLineRates
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

namespace Labelin\PitneyBowesOfficialApi\Model\Api\Model;

use \ArrayAccess;
use \Labelin\PitneyBowesOfficialApi\Model\ObjectSerializer;

/**
 * CrossBorderQuotesResponseLineRates Class Doc Comment
 *
 * @category Class
 * @package  pitneybowesShipping
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */
class CrossBorderQuotesResponseLineRates implements ModelInterface, ArrayAccess
{
    const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'CrossBorderQuotesResponse_lineRates';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'line_price' => 'float',
        'total_tax_amount' => 'float',
        'total_duty_amount' => 'int',
        'service_id' => 'string',
        'base_charge' => 'float',
        'delivery_commitment' => '\Labelin\PitneyBowesOfficialApi\Model\Api\Model\CrossBorderQuotesResponseUnitRatesDeliveryCommitment',
        'total_carrier_charge' => 'float'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPIFormats = [
        'line_price' => null,
        'total_tax_amount' => null,
        'total_duty_amount' => null,
        'service_id' => null,
        'base_charge' => null,
        'delivery_commitment' => null,
        'total_carrier_charge' => null
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
        'line_price' => 'linePrice',
        'total_tax_amount' => 'totalTaxAmount',
        'total_duty_amount' => 'totalDutyAmount',
        'service_id' => 'serviceId',
        'base_charge' => 'baseCharge',
        'delivery_commitment' => 'deliveryCommitment',
        'total_carrier_charge' => 'totalCarrierCharge'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'line_price' => 'setLinePrice',
        'total_tax_amount' => 'setTotalTaxAmount',
        'total_duty_amount' => 'setTotalDutyAmount',
        'service_id' => 'setServiceId',
        'base_charge' => 'setBaseCharge',
        'delivery_commitment' => 'setDeliveryCommitment',
        'total_carrier_charge' => 'setTotalCarrierCharge'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'line_price' => 'getLinePrice',
        'total_tax_amount' => 'getTotalTaxAmount',
        'total_duty_amount' => 'getTotalDutyAmount',
        'service_id' => 'getServiceId',
        'base_charge' => 'getBaseCharge',
        'delivery_commitment' => 'getDeliveryCommitment',
        'total_carrier_charge' => 'getTotalCarrierCharge'
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
        $this->container['line_price'] = isset($data['line_price']) ? $data['line_price'] : null;
        $this->container['total_tax_amount'] = isset($data['total_tax_amount']) ? $data['total_tax_amount'] : null;
        $this->container['total_duty_amount'] = isset($data['total_duty_amount']) ? $data['total_duty_amount'] : null;
        $this->container['service_id'] = isset($data['service_id']) ? $data['service_id'] : null;
        $this->container['base_charge'] = isset($data['base_charge']) ? $data['base_charge'] : null;
        $this->container['delivery_commitment'] = isset($data['delivery_commitment']) ? $data['delivery_commitment'] : null;
        $this->container['total_carrier_charge'] = isset($data['total_carrier_charge']) ? $data['total_carrier_charge'] : null;
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
     * Gets line_price
     *
     * @return float|null
     */
    public function getLinePrice()
    {
        return $this->container['line_price'];
    }

    /**
     * Sets line_price
     *
     * @param float|null $line_price line_price
     *
     * @return $this
     */
    public function setLinePrice($line_price)
    {
        $this->container['line_price'] = $line_price;

        return $this;
    }

    /**
     * Gets total_tax_amount
     *
     * @return float|null
     */
    public function getTotalTaxAmount()
    {
        return $this->container['total_tax_amount'];
    }

    /**
     * Sets total_tax_amount
     *
     * @param float|null $total_tax_amount total_tax_amount
     *
     * @return $this
     */
    public function setTotalTaxAmount($total_tax_amount)
    {
        $this->container['total_tax_amount'] = $total_tax_amount;

        return $this;
    }

    /**
     * Gets total_duty_amount
     *
     * @return int|null
     */
    public function getTotalDutyAmount()
    {
        return $this->container['total_duty_amount'];
    }

    /**
     * Sets total_duty_amount
     *
     * @param int|null $total_duty_amount total_duty_amount
     *
     * @return $this
     */
    public function setTotalDutyAmount($total_duty_amount)
    {
        $this->container['total_duty_amount'] = $total_duty_amount;

        return $this;
    }

    /**
     * Gets service_id
     *
     * @return string|null
     */
    public function getServiceId()
    {
        return $this->container['service_id'];
    }

    /**
     * Sets service_id
     *
     * @param string|null $service_id service_id
     *
     * @return $this
     */
    public function setServiceId($service_id)
    {
        $this->container['service_id'] = $service_id;

        return $this;
    }

    /**
     * Gets base_charge
     *
     * @return float|null
     */
    public function getBaseCharge()
    {
        return $this->container['base_charge'];
    }

    /**
     * Sets base_charge
     *
     * @param float|null $base_charge base_charge
     *
     * @return $this
     */
    public function setBaseCharge($base_charge)
    {
        $this->container['base_charge'] = $base_charge;

        return $this;
    }

    /**
     * Gets delivery_commitment
     *
     * @return \Labelin\PitneyBowesOfficialApi\Model\Api\Model\CrossBorderQuotesResponseUnitRatesDeliveryCommitment|null
     */
    public function getDeliveryCommitment()
    {
        return $this->container['delivery_commitment'];
    }

    /**
     * Sets delivery_commitment
     *
     * @param \Labelin\PitneyBowesOfficialApi\Model\Api\Model\CrossBorderQuotesResponseUnitRatesDeliveryCommitment|null $delivery_commitment delivery_commitment
     *
     * @return $this
     */
    public function setDeliveryCommitment($delivery_commitment)
    {
        $this->container['delivery_commitment'] = $delivery_commitment;

        return $this;
    }

    /**
     * Gets total_carrier_charge
     *
     * @return float|null
     */
    public function getTotalCarrierCharge()
    {
        return $this->container['total_carrier_charge'];
    }

    /**
     * Sets total_carrier_charge
     *
     * @param float|null $total_carrier_charge total_carrier_charge
     *
     * @return $this
     */
    public function setTotalCarrierCharge($total_carrier_charge)
    {
        $this->container['total_carrier_charge'] = $total_carrier_charge;

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


