<?php
/**
 * ParcelProtectionCreateResponse
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
 * ParcelProtectionCreateResponse Class Doc Comment
 *
 * @category Class
 * @package  pitneybowesShipping
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */
class ParcelProtectionCreateResponse implements ModelInterface, ArrayAccess
{
    const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'ParcelProtectionCreateResponse';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'transaction_id' => 'string',
        'parcel_protection_reference_id' => 'string',
        'parcel_protection_date' => 'string',
        'parcel_protection_fees' => 'float',
        'parcel_protection_fees_currency_code' => 'string',
        'parcel_protection_fees_breakup' => '\pitneybowesShipping\shippingApi\model\ParcelProtectionCreateResponseParcelProtectionFeesBreakup'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPIFormats = [
        'transaction_id' => null,
        'parcel_protection_reference_id' => null,
        'parcel_protection_date' => null,
        'parcel_protection_fees' => null,
        'parcel_protection_fees_currency_code' => null,
        'parcel_protection_fees_breakup' => null
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
        'transaction_id' => 'transactionID',
        'parcel_protection_reference_id' => 'parcelProtectionReferenceID',
        'parcel_protection_date' => 'parcelProtectionDate',
        'parcel_protection_fees' => 'parcelProtectionFees',
        'parcel_protection_fees_currency_code' => 'parcelProtectionFeesCurrencyCode',
        'parcel_protection_fees_breakup' => 'parcelProtectionFeesBreakup'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'transaction_id' => 'setTransactionId',
        'parcel_protection_reference_id' => 'setParcelProtectionReferenceId',
        'parcel_protection_date' => 'setParcelProtectionDate',
        'parcel_protection_fees' => 'setParcelProtectionFees',
        'parcel_protection_fees_currency_code' => 'setParcelProtectionFeesCurrencyCode',
        'parcel_protection_fees_breakup' => 'setParcelProtectionFeesBreakup'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'transaction_id' => 'getTransactionId',
        'parcel_protection_reference_id' => 'getParcelProtectionReferenceId',
        'parcel_protection_date' => 'getParcelProtectionDate',
        'parcel_protection_fees' => 'getParcelProtectionFees',
        'parcel_protection_fees_currency_code' => 'getParcelProtectionFeesCurrencyCode',
        'parcel_protection_fees_breakup' => 'getParcelProtectionFeesBreakup'
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
        $this->container['transaction_id'] = isset($data['transaction_id']) ? $data['transaction_id'] : null;
        $this->container['parcel_protection_reference_id'] = isset($data['parcel_protection_reference_id']) ? $data['parcel_protection_reference_id'] : null;
        $this->container['parcel_protection_date'] = isset($data['parcel_protection_date']) ? $data['parcel_protection_date'] : null;
        $this->container['parcel_protection_fees'] = isset($data['parcel_protection_fees']) ? $data['parcel_protection_fees'] : null;
        $this->container['parcel_protection_fees_currency_code'] = isset($data['parcel_protection_fees_currency_code']) ? $data['parcel_protection_fees_currency_code'] : null;
        $this->container['parcel_protection_fees_breakup'] = isset($data['parcel_protection_fees_breakup']) ? $data['parcel_protection_fees_breakup'] : null;
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
     * Gets transaction_id
     *
     * @return string|null
     */
    public function getTransactionId()
    {
        return $this->container['transaction_id'];
    }

    /**
     * Sets transaction_id
     *
     * @param string|null $transaction_id transaction_id
     *
     * @return $this
     */
    public function setTransactionId($transaction_id)
    {
        $this->container['transaction_id'] = $transaction_id;

        return $this;
    }

    /**
     * Gets parcel_protection_reference_id
     *
     * @return string|null
     */
    public function getParcelProtectionReferenceId()
    {
        return $this->container['parcel_protection_reference_id'];
    }

    /**
     * Sets parcel_protection_reference_id
     *
     * @param string|null $parcel_protection_reference_id parcel_protection_reference_id
     *
     * @return $this
     */
    public function setParcelProtectionReferenceId($parcel_protection_reference_id)
    {
        $this->container['parcel_protection_reference_id'] = $parcel_protection_reference_id;

        return $this;
    }

    /**
     * Gets parcel_protection_date
     *
     * @return string|null
     */
    public function getParcelProtectionDate()
    {
        return $this->container['parcel_protection_date'];
    }

    /**
     * Sets parcel_protection_date
     *
     * @param string|null $parcel_protection_date parcel_protection_date
     *
     * @return $this
     */
    public function setParcelProtectionDate($parcel_protection_date)
    {
        $this->container['parcel_protection_date'] = $parcel_protection_date;

        return $this;
    }

    /**
     * Gets parcel_protection_fees
     *
     * @return float|null
     */
    public function getParcelProtectionFees()
    {
        return $this->container['parcel_protection_fees'];
    }

    /**
     * Sets parcel_protection_fees
     *
     * @param float|null $parcel_protection_fees parcel_protection_fees
     *
     * @return $this
     */
    public function setParcelProtectionFees($parcel_protection_fees)
    {
        $this->container['parcel_protection_fees'] = $parcel_protection_fees;

        return $this;
    }

    /**
     * Gets parcel_protection_fees_currency_code
     *
     * @return string|null
     */
    public function getParcelProtectionFeesCurrencyCode()
    {
        return $this->container['parcel_protection_fees_currency_code'];
    }

    /**
     * Sets parcel_protection_fees_currency_code
     *
     * @param string|null $parcel_protection_fees_currency_code parcel_protection_fees_currency_code
     *
     * @return $this
     */
    public function setParcelProtectionFeesCurrencyCode($parcel_protection_fees_currency_code)
    {
        $this->container['parcel_protection_fees_currency_code'] = $parcel_protection_fees_currency_code;

        return $this;
    }

    /**
     * Gets parcel_protection_fees_breakup
     *
     * @return \pitneybowesShipping\shippingApi\model\ParcelProtectionCreateResponseParcelProtectionFeesBreakup|null
     */
    public function getParcelProtectionFeesBreakup()
    {
        return $this->container['parcel_protection_fees_breakup'];
    }

    /**
     * Sets parcel_protection_fees_breakup
     *
     * @param \pitneybowesShipping\shippingApi\model\ParcelProtectionCreateResponseParcelProtectionFeesBreakup|null $parcel_protection_fees_breakup parcel_protection_fees_breakup
     *
     * @return $this
     */
    public function setParcelProtectionFeesBreakup($parcel_protection_fees_breakup)
    {
        $this->container['parcel_protection_fees_breakup'] = $parcel_protection_fees_breakup;

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


