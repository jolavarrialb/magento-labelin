<?php
/**
 * CarrierPayment
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
 * CarrierPayment Class Doc Comment
 *
 * @category Class
 * @package  pitneybowesShipping
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */
class CarrierPayment implements ModelInterface, ArrayAccess
{
    const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'CarrierPayment';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'account_number' => 'string',
        'country_code' => 'string',
        'party' => 'string',
        'postal_code' => 'string',
        'type_of_charge' => 'string'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPIFormats = [
        'account_number' => null,
        'country_code' => null,
        'party' => null,
        'postal_code' => null,
        'type_of_charge' => null
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
        'account_number' => 'accountNumber',
        'country_code' => 'countryCode',
        'party' => 'party',
        'postal_code' => 'postalCode',
        'type_of_charge' => 'typeOfCharge'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'account_number' => 'setAccountNumber',
        'country_code' => 'setCountryCode',
        'party' => 'setParty',
        'postal_code' => 'setPostalCode',
        'type_of_charge' => 'setTypeOfCharge'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'account_number' => 'getAccountNumber',
        'country_code' => 'getCountryCode',
        'party' => 'getParty',
        'postal_code' => 'getPostalCode',
        'type_of_charge' => 'getTypeOfCharge'
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

    const PARTY_RECEIVER = 'BILL_RECEIVER';
    const PARTY_SENDER = 'BILL_SENDER';
    const PARTY_THIRD_PARTY = 'BILL_THIRD_PARTY';
    const PARTY_RECEIVER_CONTRACT = 'BILL_RECEIVER_CONTRACT';
    const TYPE_OF_CHARGE_TRANSPORTATION_CHARGES = 'TRANSPORTATION_CHARGES';
    const TYPE_OF_CHARGE_DUTIES_AND_TAXES = 'DUTIES_AND_TAXES';
    const TYPE_OF_CHARGE_ALL_CHARGES = 'ALL_CHARGES';



    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getPartyAllowableValues()
    {
        return [
            self::PARTY_RECEIVER,
            self::PARTY_SENDER,
            self::PARTY_THIRD_PARTY,
            self::PARTY_RECEIVER_CONTRACT,
        ];
    }

    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getTypeOfChargeAllowableValues()
    {
        return [
            self::TYPE_OF_CHARGE_TRANSPORTATION_CHARGES,
            self::TYPE_OF_CHARGE_DUTIES_AND_TAXES,
            self::TYPE_OF_CHARGE_ALL_CHARGES,
        ];
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
        $this->container['account_number'] = isset($data['account_number']) ? $data['account_number'] : null;
        $this->container['country_code'] = isset($data['country_code']) ? $data['country_code'] : null;
        $this->container['party'] = isset($data['party']) ? $data['party'] : null;
        $this->container['postal_code'] = isset($data['postal_code']) ? $data['postal_code'] : null;
        $this->container['type_of_charge'] = isset($data['type_of_charge']) ? $data['type_of_charge'] : null;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];

        if ($this->container['party'] === null) {
            $invalidProperties[] = "'party' can't be null";
        }
        $allowedValues = $this->getPartyAllowableValues();
        if (!is_null($this->container['party']) && !in_array($this->container['party'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value for 'party', must be one of '%s'",
                implode("', '", $allowedValues)
            );
        }

        if ($this->container['type_of_charge'] === null) {
            $invalidProperties[] = "'type_of_charge' can't be null";
        }
        $allowedValues = $this->getTypeOfChargeAllowableValues();
        if (!is_null($this->container['type_of_charge']) && !in_array($this->container['type_of_charge'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value for 'type_of_charge', must be one of '%s'",
                implode("', '", $allowedValues)
            );
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
     * Gets account_number
     *
     * @return string|null
     */
    public function getAccountNumber()
    {
        return $this->container['account_number'];
    }

    /**
     * Sets account_number
     *
     * @param string|null $account_number account_number
     *
     * @return $this
     */
    public function setAccountNumber($account_number)
    {
        $this->container['account_number'] = $account_number;

        return $this;
    }

    /**
     * Gets country_code
     *
     * @return string|null
     */
    public function getCountryCode()
    {
        return $this->container['country_code'];
    }

    /**
     * Sets country_code
     *
     * @param string|null $country_code country_code
     *
     * @return $this
     */
    public function setCountryCode($country_code)
    {
        $this->container['country_code'] = $country_code;

        return $this;
    }

    /**
     * Gets party
     *
     * @return string
     */
    public function getParty()
    {
        return $this->container['party'];
    }

    /**
     * Sets party
     *
     * @param string $party party
     *
     * @return $this
     */
    public function setParty($party)
    {
        $allowedValues = $this->getPartyAllowableValues();
        if (!in_array($party, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value for 'party', must be one of '%s'",
                    implode("', '", $allowedValues)
                )
            );
        }
        $this->container['party'] = $party;

        return $this;
    }

    /**
     * Gets postal_code
     *
     * @return string|null
     */
    public function getPostalCode()
    {
        return $this->container['postal_code'];
    }

    /**
     * Sets postal_code
     *
     * @param string|null $postal_code postal_code
     *
     * @return $this
     */
    public function setPostalCode($postal_code)
    {
        $this->container['postal_code'] = $postal_code;

        return $this;
    }

    /**
     * Gets type_of_charge
     *
     * @return string
     */
    public function getTypeOfCharge()
    {
        return $this->container['type_of_charge'];
    }

    /**
     * Sets type_of_charge
     *
     * @param string $type_of_charge type_of_charge
     *
     * @return $this
     */
    public function setTypeOfCharge($type_of_charge)
    {
        $allowedValues = $this->getTypeOfChargeAllowableValues();
        if (!in_array($type_of_charge, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value for 'type_of_charge', must be one of '%s'",
                    implode("', '", $allowedValues)
                )
            );
        }
        $this->container['type_of_charge'] = $type_of_charge;

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


