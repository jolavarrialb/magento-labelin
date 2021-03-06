<?php
/**
 * CustomsItem
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
 * CustomsItem Class Doc Comment
 *
 * @category Class
 * @package  pitneybowesShipping
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */
class CustomsItem implements ModelInterface, ArrayAccess
{
    const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'CustomsItem';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'description' => 'string',
        'h_s_tariff_code' => 'string',
        'net_cost_method' => 'string',
        'origin_country_code' => 'string',
        'origin_state_province' => 'string',
        'preference_criterion' => 'string',
        'producer_address' => '\Labelin\PitneyBowesOfficialApi\Model\Api\Model\Address',
        'producer_determination' => 'string',
        'producer_id' => 'string',
        'quantity' => 'int',
        'quantity_uom' => 'string',
        'unit_price' => 'float',
        'unit_weight' => '\Labelin\PitneyBowesOfficialApi\Model\Api\Model\ParcelWeight'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPIFormats = [
        'description' => null,
        'h_s_tariff_code' => null,
        'net_cost_method' => null,
        'origin_country_code' => null,
        'origin_state_province' => null,
        'preference_criterion' => null,
        'producer_address' => null,
        'producer_determination' => null,
        'producer_id' => null,
        'quantity' => 'int32',
        'quantity_uom' => null,
        'unit_price' => null,
        'unit_weight' => null
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
        'description' => 'description',
        'h_s_tariff_code' => 'hSTariffCode',
        'net_cost_method' => 'netCostMethod',
        'origin_country_code' => 'originCountryCode',
        'origin_state_province' => 'originStateProvince',
        'preference_criterion' => 'preferenceCriterion',
        'producer_address' => 'producerAddress',
        'producer_determination' => 'producerDetermination',
        'producer_id' => 'producerId',
        'quantity' => 'quantity',
        'quantity_uom' => 'quantityUOM',
        'unit_price' => 'unitPrice',
        'unit_weight' => 'unitWeight'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'description' => 'setDescription',
        'h_s_tariff_code' => 'setHSTariffCode',
        'net_cost_method' => 'setNetCostMethod',
        'origin_country_code' => 'setOriginCountryCode',
        'origin_state_province' => 'setOriginStateProvince',
        'preference_criterion' => 'setPreferenceCriterion',
        'producer_address' => 'setProducerAddress',
        'producer_determination' => 'setProducerDetermination',
        'producer_id' => 'setProducerId',
        'quantity' => 'setQuantity',
        'quantity_uom' => 'setQuantityUom',
        'unit_price' => 'setUnitPrice',
        'unit_weight' => 'setUnitWeight'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'description' => 'getDescription',
        'h_s_tariff_code' => 'getHSTariffCode',
        'net_cost_method' => 'getNetCostMethod',
        'origin_country_code' => 'getOriginCountryCode',
        'origin_state_province' => 'getOriginStateProvince',
        'preference_criterion' => 'getPreferenceCriterion',
        'producer_address' => 'getProducerAddress',
        'producer_determination' => 'getProducerDetermination',
        'producer_id' => 'getProducerId',
        'quantity' => 'getQuantity',
        'quantity_uom' => 'getQuantityUom',
        'unit_price' => 'getUnitPrice',
        'unit_weight' => 'getUnitWeight'
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

    const NET_COST_METHOD_NO_NET_COST = 'NO_NET_COST';
    const NET_COST_METHOD_NET_COST = 'NET_COST';
    const PREFERENCE_CRITERION_A = 'A';
    const PREFERENCE_CRITERION_B = 'B';
    const PREFERENCE_CRITERION_C = 'C';
    const PREFERENCE_CRITERION_D = 'D';
    const PREFERENCE_CRITERION_E = 'E';
    const PREFERENCE_CRITERION_F = 'F';
    const PRODUCER_DETERMINATION_NO_1 = 'NO_1';
    const PRODUCER_DETERMINATION_NO_2 = 'NO_2';
    const PRODUCER_DETERMINATION_NO_3 = 'NO_3';
    const PRODUCER_DETERMINATION_PD_YES = 'PD_YES';



    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getNetCostMethodAllowableValues()
    {
        return [
            self::NET_COST_METHOD_NO_NET_COST,
            self::NET_COST_METHOD_NET_COST,
        ];
    }

    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getPreferenceCriterionAllowableValues()
    {
        return [
            self::PREFERENCE_CRITERION_A,
            self::PREFERENCE_CRITERION_B,
            self::PREFERENCE_CRITERION_C,
            self::PREFERENCE_CRITERION_D,
            self::PREFERENCE_CRITERION_E,
            self::PREFERENCE_CRITERION_F,
        ];
    }

    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getProducerDeterminationAllowableValues()
    {
        return [
            self::PRODUCER_DETERMINATION_NO_1,
            self::PRODUCER_DETERMINATION_NO_2,
            self::PRODUCER_DETERMINATION_NO_3,
            self::PRODUCER_DETERMINATION_PD_YES,
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
        $this->container['description'] = isset($data['description']) ? $data['description'] : null;
        $this->container['h_s_tariff_code'] = isset($data['h_s_tariff_code']) ? $data['h_s_tariff_code'] : null;
        $this->container['net_cost_method'] = isset($data['net_cost_method']) ? $data['net_cost_method'] : null;
        $this->container['origin_country_code'] = isset($data['origin_country_code']) ? $data['origin_country_code'] : null;
        $this->container['origin_state_province'] = isset($data['origin_state_province']) ? $data['origin_state_province'] : null;
        $this->container['preference_criterion'] = isset($data['preference_criterion']) ? $data['preference_criterion'] : null;
        $this->container['producer_address'] = isset($data['producer_address']) ? $data['producer_address'] : null;
        $this->container['producer_determination'] = isset($data['producer_determination']) ? $data['producer_determination'] : null;
        $this->container['producer_id'] = isset($data['producer_id']) ? $data['producer_id'] : null;
        $this->container['quantity'] = isset($data['quantity']) ? $data['quantity'] : null;
        $this->container['quantity_uom'] = isset($data['quantity_uom']) ? $data['quantity_uom'] : null;
        $this->container['unit_price'] = isset($data['unit_price']) ? $data['unit_price'] : null;
        $this->container['unit_weight'] = isset($data['unit_weight']) ? $data['unit_weight'] : null;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];

        if ($this->container['description'] === null) {
            $invalidProperties[] = "'description' can't be null";
        }
        $allowedValues = $this->getNetCostMethodAllowableValues();
        if (!is_null($this->container['net_cost_method']) && !in_array($this->container['net_cost_method'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value for 'net_cost_method', must be one of '%s'",
                implode("', '", $allowedValues)
            );
        }

        if ($this->container['origin_country_code'] === null) {
            $invalidProperties[] = "'origin_country_code' can't be null";
        }
        $allowedValues = $this->getPreferenceCriterionAllowableValues();
        if (!is_null($this->container['preference_criterion']) && !in_array($this->container['preference_criterion'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value for 'preference_criterion', must be one of '%s'",
                implode("', '", $allowedValues)
            );
        }

        $allowedValues = $this->getProducerDeterminationAllowableValues();
        if (!is_null($this->container['producer_determination']) && !in_array($this->container['producer_determination'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value for 'producer_determination', must be one of '%s'",
                implode("', '", $allowedValues)
            );
        }

        if ($this->container['quantity'] === null) {
            $invalidProperties[] = "'quantity' can't be null";
        }
        if ($this->container['unit_price'] === null) {
            $invalidProperties[] = "'unit_price' can't be null";
        }
        if ($this->container['unit_weight'] === null) {
            $invalidProperties[] = "'unit_weight' can't be null";
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
     * Gets description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->container['description'];
    }

    /**
     * Sets description
     *
     * @param string $description description
     *
     * @return $this
     */
    public function setDescription($description)
    {
        $this->container['description'] = $description;

        return $this;
    }

    /**
     * Gets h_s_tariff_code
     *
     * @return string|null
     */
    public function getHSTariffCode()
    {
        return $this->container['h_s_tariff_code'];
    }

    /**
     * Sets h_s_tariff_code
     *
     * @param string|null $h_s_tariff_code h_s_tariff_code
     *
     * @return $this
     */
    public function setHSTariffCode($h_s_tariff_code)
    {
        $this->container['h_s_tariff_code'] = $h_s_tariff_code;

        return $this;
    }

    /**
     * Gets net_cost_method
     *
     * @return string|null
     */
    public function getNetCostMethod()
    {
        return $this->container['net_cost_method'];
    }

    /**
     * Sets net_cost_method
     *
     * @param string|null $net_cost_method net_cost_method
     *
     * @return $this
     */
    public function setNetCostMethod($net_cost_method)
    {
        $allowedValues = $this->getNetCostMethodAllowableValues();
        if (!is_null($net_cost_method) && !in_array($net_cost_method, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value for 'net_cost_method', must be one of '%s'",
                    implode("', '", $allowedValues)
                )
            );
        }
        $this->container['net_cost_method'] = $net_cost_method;

        return $this;
    }

    /**
     * Gets origin_country_code
     *
     * @return string
     */
    public function getOriginCountryCode()
    {
        return $this->container['origin_country_code'];
    }

    /**
     * Sets origin_country_code
     *
     * @param string $origin_country_code origin_country_code
     *
     * @return $this
     */
    public function setOriginCountryCode($origin_country_code)
    {
        $this->container['origin_country_code'] = $origin_country_code;

        return $this;
    }

    /**
     * Gets origin_state_province
     *
     * @return string|null
     */
    public function getOriginStateProvince()
    {
        return $this->container['origin_state_province'];
    }

    /**
     * Sets origin_state_province
     *
     * @param string|null $origin_state_province origin_state_province
     *
     * @return $this
     */
    public function setOriginStateProvince($origin_state_province)
    {
        $this->container['origin_state_province'] = $origin_state_province;

        return $this;
    }

    /**
     * Gets preference_criterion
     *
     * @return string|null
     */
    public function getPreferenceCriterion()
    {
        return $this->container['preference_criterion'];
    }

    /**
     * Sets preference_criterion
     *
     * @param string|null $preference_criterion preference_criterion
     *
     * @return $this
     */
    public function setPreferenceCriterion($preference_criterion)
    {
        $allowedValues = $this->getPreferenceCriterionAllowableValues();
        if (!is_null($preference_criterion) && !in_array($preference_criterion, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value for 'preference_criterion', must be one of '%s'",
                    implode("', '", $allowedValues)
                )
            );
        }
        $this->container['preference_criterion'] = $preference_criterion;

        return $this;
    }

    /**
     * Gets producer_address
     *
     * @return \Labelin\PitneyBowesOfficialApi\Model\Api\Model\Address|null
     */
    public function getProducerAddress()
    {
        return $this->container['producer_address'];
    }

    /**
     * Sets producer_address
     *
     * @param \Labelin\PitneyBowesOfficialApi\Model\Api\Model\Address|null $producer_address producer_address
     *
     * @return $this
     */
    public function setProducerAddress($producer_address)
    {
        $this->container['producer_address'] = $producer_address;

        return $this;
    }

    /**
     * Gets producer_determination
     *
     * @return string|null
     */
    public function getProducerDetermination()
    {
        return $this->container['producer_determination'];
    }

    /**
     * Sets producer_determination
     *
     * @param string|null $producer_determination producer_determination
     *
     * @return $this
     */
    public function setProducerDetermination($producer_determination)
    {
        $allowedValues = $this->getProducerDeterminationAllowableValues();
        if (!is_null($producer_determination) && !in_array($producer_determination, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value for 'producer_determination', must be one of '%s'",
                    implode("', '", $allowedValues)
                )
            );
        }
        $this->container['producer_determination'] = $producer_determination;

        return $this;
    }

    /**
     * Gets producer_id
     *
     * @return string|null
     */
    public function getProducerId()
    {
        return $this->container['producer_id'];
    }

    /**
     * Sets producer_id
     *
     * @param string|null $producer_id producer_id
     *
     * @return $this
     */
    public function setProducerId($producer_id)
    {
        $this->container['producer_id'] = $producer_id;

        return $this;
    }

    /**
     * Gets quantity
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->container['quantity'];
    }

    /**
     * Sets quantity
     *
     * @param int $quantity quantity
     *
     * @return $this
     */
    public function setQuantity($quantity)
    {
        $this->container['quantity'] = $quantity;

        return $this;
    }

    /**
     * Gets quantity_uom
     *
     * @return string|null
     */
    public function getQuantityUom()
    {
        return $this->container['quantity_uom'];
    }

    /**
     * Sets quantity_uom
     *
     * @param string|null $quantity_uom quantity_uom
     *
     * @return $this
     */
    public function setQuantityUom($quantity_uom)
    {
        $this->container['quantity_uom'] = $quantity_uom;

        return $this;
    }

    /**
     * Gets unit_price
     *
     * @return float
     */
    public function getUnitPrice()
    {
        return $this->container['unit_price'];
    }

    /**
     * Sets unit_price
     *
     * @param float $unit_price unit_price
     *
     * @return $this
     */
    public function setUnitPrice($unit_price)
    {
        $this->container['unit_price'] = $unit_price;

        return $this;
    }

    /**
     * Gets unit_weight
     *
     * @return \Labelin\PitneyBowesOfficialApi\Model\Api\Model\ParcelWeight
     */
    public function getUnitWeight()
    {
        return $this->container['unit_weight'];
    }

    /**
     * Sets unit_weight
     *
     * @param \Labelin\PitneyBowesOfficialApi\Model\Api\Model\ParcelWeight $unit_weight unit_weight
     *
     * @return $this
     */
    public function setUnitWeight($unit_weight)
    {
        $this->container['unit_weight'] = $unit_weight;

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


