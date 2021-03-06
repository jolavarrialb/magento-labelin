<?php
/**
 * Document
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
 * Document Class Doc Comment
 *
 * @category Class
 * @package  pitneybowesShipping
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */
class Document implements ModelInterface, ArrayAccess
{
    const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'Document';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'content_type' => 'string',
        'contents' => 'string',
        'doc_tab' => '\Labelin\PitneyBowesOfficialApi\Model\Api\Model\DocTabItem[]',
        'file_format' => 'string',
        'pages' => '\Labelin\PitneyBowesOfficialApi\Model\Api\Model\DocumentPage[]',
        'print_dialog_option' => 'string',
        'resolution' => 'string',
        'size' => 'string',
        'type' => 'string'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPIFormats = [
        'content_type' => null,
        'contents' => null,
        'doc_tab' => null,
        'file_format' => null,
        'pages' => null,
        'print_dialog_option' => null,
        'resolution' => null,
        'size' => null,
        'type' => null
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
        'content_type' => 'contentType',
        'contents' => 'contents',
        'doc_tab' => 'docTab',
        'file_format' => 'fileFormat',
        'pages' => 'pages',
        'print_dialog_option' => 'printDialogOption',
        'resolution' => 'resolution',
        'size' => 'size',
        'type' => 'type'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'content_type' => 'setContentType',
        'contents' => 'setContents',
        'doc_tab' => 'setDocTab',
        'file_format' => 'setFileFormat',
        'pages' => 'setPages',
        'print_dialog_option' => 'setPrintDialogOption',
        'resolution' => 'setResolution',
        'size' => 'setSize',
        'type' => 'setType'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'content_type' => 'getContentType',
        'contents' => 'getContents',
        'doc_tab' => 'getDocTab',
        'file_format' => 'getFileFormat',
        'pages' => 'getPages',
        'print_dialog_option' => 'getPrintDialogOption',
        'resolution' => 'getResolution',
        'size' => 'getSize',
        'type' => 'getType'
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

    const CONTENT_TYPE_URL = 'URL';
    const CONTENT_TYPE_BASE64 = 'BASE64';
    const FILE_FORMAT_PDF = 'PDF';
    const FILE_FORMAT_PNG = 'PNG';
    const FILE_FORMAT_GIF = 'GIF';
    const FILE_FORMAT_ZPL = 'ZPL';
    const FILE_FORMAT_ZPL2 = 'ZPL2';
    const PRINT_DIALOG_OPTION_NO_PRINT_DIALOG = 'NO_PRINT_DIALOG';
    const PRINT_DIALOG_OPTION_EMBED_PRINT_DIALOG = 'EMBED_PRINT_DIALOG';
    const RESOLUTION__300 = 'DPI_300';
    const RESOLUTION__203 = 'DPI_203';
    const RESOLUTION__150 = 'DPI_150';
    const SIZE__2_X7 = 'DOC_2X7';
    const SIZE__4_X1 = 'DOC_4X1';
    const SIZE__4_X3 = 'DOC_4X3';
    const SIZE__4_X6 = 'DOC_4X6';
    const SIZE__4_X8 = 'DOC_4X8';
    const SIZE__6_X4 = 'DOC_6X4';
    const SIZE__8_X11 = 'DOC_8X11';
    const SIZE__9_X4 = 'DOC_9X4';
    const SIZE__4_X5 = 'DOC_4X5';
    const SIZE__8_5_X5_5 = 'DOC_8_5X5_5';



    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getContentTypeAllowableValues()
    {
        return [
            self::CONTENT_TYPE_URL,
            self::CONTENT_TYPE_BASE64,
        ];
    }

    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getFileFormatAllowableValues()
    {
        return [
            self::FILE_FORMAT_PDF,
            self::FILE_FORMAT_PNG,
            self::FILE_FORMAT_GIF,
            self::FILE_FORMAT_ZPL,
            self::FILE_FORMAT_ZPL2,
        ];
    }

    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getPrintDialogOptionAllowableValues()
    {
        return [
            self::PRINT_DIALOG_OPTION_NO_PRINT_DIALOG,
            self::PRINT_DIALOG_OPTION_EMBED_PRINT_DIALOG,
        ];
    }

    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getResolutionAllowableValues()
    {
        return [
            self::RESOLUTION__300,
            self::RESOLUTION__203,
            self::RESOLUTION__150,
        ];
    }

    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getSizeAllowableValues()
    {
        return [
            self::SIZE__2_X7,
            self::SIZE__4_X1,
            self::SIZE__4_X3,
            self::SIZE__4_X6,
            self::SIZE__4_X8,
            self::SIZE__6_X4,
            self::SIZE__8_X11,
            self::SIZE__9_X4,
            self::SIZE__4_X5,
            self::SIZE__8_5_X5_5,
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
        $this->container['content_type'] = isset($data['content_type']) ? $data['content_type'] : null;
        $this->container['contents'] = isset($data['contents']) ? $data['contents'] : null;
        $this->container['doc_tab'] = isset($data['doc_tab']) ? $data['doc_tab'] : null;
        $this->container['file_format'] = isset($data['file_format']) ? $data['file_format'] : null;
        $this->container['pages'] = isset($data['pages']) ? $data['pages'] : null;
        $this->container['print_dialog_option'] = isset($data['print_dialog_option']) ? $data['print_dialog_option'] : null;
        $this->container['resolution'] = isset($data['resolution']) ? $data['resolution'] : null;
        $this->container['size'] = isset($data['size']) ? $data['size'] : null;
        $this->container['type'] = isset($data['type']) ? $data['type'] : null;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];

        $allowedValues = $this->getContentTypeAllowableValues();
        if (!is_null($this->container['content_type']) && !in_array($this->container['content_type'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value for 'content_type', must be one of '%s'",
                implode("', '", $allowedValues)
            );
        }

        $allowedValues = $this->getFileFormatAllowableValues();
        if (!is_null($this->container['file_format']) && !in_array($this->container['file_format'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value for 'file_format', must be one of '%s'",
                implode("', '", $allowedValues)
            );
        }

        $allowedValues = $this->getPrintDialogOptionAllowableValues();
        if (!is_null($this->container['print_dialog_option']) && !in_array($this->container['print_dialog_option'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value for 'print_dialog_option', must be one of '%s'",
                implode("', '", $allowedValues)
            );
        }

        $allowedValues = $this->getResolutionAllowableValues();
        if (!is_null($this->container['resolution']) && !in_array($this->container['resolution'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value for 'resolution', must be one of '%s'",
                implode("', '", $allowedValues)
            );
        }

        $allowedValues = $this->getSizeAllowableValues();
        if (!is_null($this->container['size']) && !in_array($this->container['size'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value for 'size', must be one of '%s'",
                implode("', '", $allowedValues)
            );
        }

        if ($this->container['type'] === null) {
            $invalidProperties[] = "'type' can't be null";
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
     * Gets content_type
     *
     * @return string|null
     */
    public function getContentType()
    {
        return $this->container['content_type'];
    }

    /**
     * Sets content_type
     *
     * @param string|null $content_type content_type
     *
     * @return $this
     */
    public function setContentType($content_type)
    {
        $allowedValues = $this->getContentTypeAllowableValues();
        if (!is_null($content_type) && !in_array($content_type, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value for 'content_type', must be one of '%s'",
                    implode("', '", $allowedValues)
                )
            );
        }
        $this->container['content_type'] = $content_type;

        return $this;
    }

    /**
     * Gets contents
     *
     * @return string|null
     */
    public function getContents()
    {
        return $this->container['contents'];
    }

    /**
     * Sets contents
     *
     * @param string|null $contents contents
     *
     * @return $this
     */
    public function setContents($contents)
    {
        $this->container['contents'] = $contents;

        return $this;
    }

    /**
     * Gets doc_tab
     *
     * @return \Labelin\PitneyBowesOfficialApi\Model\Api\Model\DocTabItem[]|null
     */
    public function getDocTab()
    {
        return $this->container['doc_tab'];
    }

    /**
     * Sets doc_tab
     *
     * @param \Labelin\PitneyBowesOfficialApi\Model\Api\Model\DocTabItem[]|null $doc_tab doc_tab
     *
     * @return $this
     */
    public function setDocTab($doc_tab)
    {
        $this->container['doc_tab'] = $doc_tab;

        return $this;
    }

    /**
     * Gets file_format
     *
     * @return string|null
     */
    public function getFileFormat()
    {
        return $this->container['file_format'];
    }

    /**
     * Sets file_format
     *
     * @param string|null $file_format file_format
     *
     * @return $this
     */
    public function setFileFormat($file_format)
    {
        $allowedValues = $this->getFileFormatAllowableValues();
        if (!is_null($file_format) && !in_array($file_format, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value for 'file_format', must be one of '%s'",
                    implode("', '", $allowedValues)
                )
            );
        }
        $this->container['file_format'] = $file_format;

        return $this;
    }

    /**
     * Gets pages
     *
     * @return \Labelin\PitneyBowesOfficialApi\Model\Api\Model\DocumentPage[]|null
     */
    public function getPages()
    {
        return $this->container['pages'];
    }

    /**
     * Sets pages
     *
     * @param \Labelin\PitneyBowesOfficialApi\Model\Api\Model\DocumentPage[]|null $pages pages
     *
     * @return $this
     */
    public function setPages($pages)
    {
        $this->container['pages'] = $pages;

        return $this;
    }

    /**
     * Gets print_dialog_option
     *
     * @return string|null
     */
    public function getPrintDialogOption()
    {
        return $this->container['print_dialog_option'];
    }

    /**
     * Sets print_dialog_option
     *
     * @param string|null $print_dialog_option print_dialog_option
     *
     * @return $this
     */
    public function setPrintDialogOption($print_dialog_option)
    {
        $allowedValues = $this->getPrintDialogOptionAllowableValues();
        if (!is_null($print_dialog_option) && !in_array($print_dialog_option, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value for 'print_dialog_option', must be one of '%s'",
                    implode("', '", $allowedValues)
                )
            );
        }
        $this->container['print_dialog_option'] = $print_dialog_option;

        return $this;
    }

    /**
     * Gets resolution
     *
     * @return string|null
     */
    public function getResolution()
    {
        return $this->container['resolution'];
    }

    /**
     * Sets resolution
     *
     * @param string|null $resolution resolution
     *
     * @return $this
     */
    public function setResolution($resolution)
    {
        $allowedValues = $this->getResolutionAllowableValues();
        if (!is_null($resolution) && !in_array($resolution, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value for 'resolution', must be one of '%s'",
                    implode("', '", $allowedValues)
                )
            );
        }
        $this->container['resolution'] = $resolution;

        return $this;
    }

    /**
     * Gets size
     *
     * @return string|null
     */
    public function getSize()
    {
        return $this->container['size'];
    }

    /**
     * Sets size
     *
     * @param string|null $size size
     *
     * @return $this
     */
    public function setSize($size)
    {
        $allowedValues = $this->getSizeAllowableValues();
        if (!is_null($size) && !in_array($size, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value for 'size', must be one of '%s'",
                    implode("', '", $allowedValues)
                )
            );
        }
        $this->container['size'] = $size;

        return $this;
    }

    /**
     * Gets type
     *
     * @return string
     */
    public function getType()
    {
        return $this->container['type'];
    }

    /**
     * Sets type
     *
     * @param string $type type
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->container['type'] = $type;

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


