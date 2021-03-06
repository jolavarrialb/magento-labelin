<?php
/**
 * ParcelProtectionPolicyResponseSort
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
 * ParcelProtectionPolicyResponseSort Class Doc Comment
 *
 * @category Class
 * @package  pitneybowesShipping
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */
class ParcelProtectionPolicyResponseSort implements ModelInterface, ArrayAccess
{
    const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'ParcelProtectionPolicyResponse_sort';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'direction' => 'string',
        'property' => 'string',
        'ignore_case' => 'bool',
        'null_handling' => 'string',
        'descending' => 'bool',
        'ascending' => 'bool'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPIFormats = [
        'direction' => null,
        'property' => null,
        'ignore_case' => null,
        'null_handling' => null,
        'descending' => null,
        'ascending' => null
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
        'direction' => 'direction',
        'property' => 'property',
        'ignore_case' => 'ignoreCase',
        'null_handling' => 'nullHandling',
        'descending' => 'descending',
        'ascending' => 'ascending'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'direction' => 'setDirection',
        'property' => 'setProperty',
        'ignore_case' => 'setIgnoreCase',
        'null_handling' => 'setNullHandling',
        'descending' => 'setDescending',
        'ascending' => 'setAscending'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'direction' => 'getDirection',
        'property' => 'getProperty',
        'ignore_case' => 'getIgnoreCase',
        'null_handling' => 'getNullHandling',
        'descending' => 'getDescending',
        'ascending' => 'getAscending'
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
        $this->container['direction'] = isset($data['direction']) ? $data['direction'] : null;
        $this->container['property'] = isset($data['property']) ? $data['property'] : null;
        $this->container['ignore_case'] = isset($data['ignore_case']) ? $data['ignore_case'] : null;
        $this->container['null_handling'] = isset($data['null_handling']) ? $data['null_handling'] : null;
        $this->container['descending'] = isset($data['descending']) ? $data['descending'] : null;
        $this->container['ascending'] = isset($data['ascending']) ? $data['ascending'] : null;
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
     * Gets direction
     *
     * @return string|null
     */
    public function getDirection()
    {
        return $this->container['direction'];
    }

    /**
     * Sets direction
     *
     * @param string|null $direction direction
     *
     * @return $this
     */
    public function setDirection($direction)
    {
        $this->container['direction'] = $direction;

        return $this;
    }

    /**
     * Gets property
     *
     * @return string|null
     */
    public function getProperty()
    {
        return $this->container['property'];
    }

    /**
     * Sets property
     *
     * @param string|null $property property
     *
     * @return $this
     */
    public function setProperty($property)
    {
        $this->container['property'] = $property;

        return $this;
    }

    /**
     * Gets ignore_case
     *
     * @return bool|null
     */
    public function getIgnoreCase()
    {
        return $this->container['ignore_case'];
    }

    /**
     * Sets ignore_case
     *
     * @param bool|null $ignore_case ignore_case
     *
     * @return $this
     */
    public function setIgnoreCase($ignore_case)
    {
        $this->container['ignore_case'] = $ignore_case;

        return $this;
    }

    /**
     * Gets null_handling
     *
     * @return string|null
     */
    public function getNullHandling()
    {
        return $this->container['null_handling'];
    }

    /**
     * Sets null_handling
     *
     * @param string|null $null_handling null_handling
     *
     * @return $this
     */
    public function setNullHandling($null_handling)
    {
        $this->container['null_handling'] = $null_handling;

        return $this;
    }

    /**
     * Gets descending
     *
     * @return bool|null
     */
    public function getDescending()
    {
        return $this->container['descending'];
    }

    /**
     * Sets descending
     *
     * @param bool|null $descending descending
     *
     * @return $this
     */
    public function setDescending($descending)
    {
        $this->container['descending'] = $descending;

        return $this;
    }

    /**
     * Gets ascending
     *
     * @return bool|null
     */
    public function getAscending()
    {
        return $this->container['ascending'];
    }

    /**
     * Sets ascending
     *
     * @param bool|null $ascending ascending
     *
     * @return $this
     */
    public function setAscending($ascending)
    {
        $this->container['ascending'] = $ascending;

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


