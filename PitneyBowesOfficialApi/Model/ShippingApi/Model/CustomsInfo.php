<?php
/**
 * CustomsInfo
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
 * CustomsInfo Class Doc Comment
 *
 * @category Class
 * @package  pitneybowesShipping
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */
class CustomsInfo implements ModelInterface, ArrayAccess
{
    const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'CustomsInfo';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'eelpfc' => 'string',
        'blanket_end_date' => 'string',
        'blanket_start_date' => 'string',
        'certificate_number' => 'string',
        'comments' => 'string',
        'currency_code' => 'string',
        'customs_declared_value' => 'float',
        'declaration_statement' => 'string',
        'freight_charge' => 'float',
        'from_customs_reference' => 'string',
        'handling_costs' => 'float',
        'importer_customs_reference' => 'string',
        'insured_amount' => 'float',
        'insured_number' => 'string',
        'invoice_number' => 'string',
        'license_number' => 'string',
        'other_charge' => 'float',
        'other_description' => 'string',
        'other_type' => 'string',
        'packing_costs' => 'float',
        'producer_specification' => 'string',
        'reason_for_export' => 'string',
        'reason_for_export_explanation' => 'string',
        'sdr_value' => 'float',
        'shipping_document_type' => 'string',
        'signature_contact' => '\pitneybowesShipping\shippingApi\model\Address',
        'terms_of_sale' => 'string'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPIFormats = [
        'eelpfc' => null,
        'blanket_end_date' => null,
        'blanket_start_date' => null,
        'certificate_number' => null,
        'comments' => null,
        'currency_code' => null,
        'customs_declared_value' => null,
        'declaration_statement' => null,
        'freight_charge' => null,
        'from_customs_reference' => null,
        'handling_costs' => null,
        'importer_customs_reference' => null,
        'insured_amount' => null,
        'insured_number' => null,
        'invoice_number' => null,
        'license_number' => null,
        'other_charge' => null,
        'other_description' => null,
        'other_type' => null,
        'packing_costs' => null,
        'producer_specification' => null,
        'reason_for_export' => null,
        'reason_for_export_explanation' => null,
        'sdr_value' => null,
        'shipping_document_type' => null,
        'signature_contact' => null,
        'terms_of_sale' => null
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
        'eelpfc' => 'EELPFC',
        'blanket_end_date' => 'blanketEndDate',
        'blanket_start_date' => 'blanketStartDate',
        'certificate_number' => 'certificateNumber',
        'comments' => 'comments',
        'currency_code' => 'currencyCode',
        'customs_declared_value' => 'customsDeclaredValue',
        'declaration_statement' => 'declarationStatement',
        'freight_charge' => 'freightCharge',
        'from_customs_reference' => 'fromCustomsReference',
        'handling_costs' => 'handlingCosts',
        'importer_customs_reference' => 'importerCustomsReference',
        'insured_amount' => 'insuredAmount',
        'insured_number' => 'insuredNumber',
        'invoice_number' => 'invoiceNumber',
        'license_number' => 'licenseNumber',
        'other_charge' => 'otherCharge',
        'other_description' => 'otherDescription',
        'other_type' => 'otherType',
        'packing_costs' => 'packingCosts',
        'producer_specification' => 'producerSpecification',
        'reason_for_export' => 'reasonForExport',
        'reason_for_export_explanation' => 'reasonForExportExplanation',
        'sdr_value' => 'sdrValue',
        'shipping_document_type' => 'shippingDocumentType',
        'signature_contact' => 'signatureContact',
        'terms_of_sale' => 'termsOfSale'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'eelpfc' => 'setEelpfc',
        'blanket_end_date' => 'setBlanketEndDate',
        'blanket_start_date' => 'setBlanketStartDate',
        'certificate_number' => 'setCertificateNumber',
        'comments' => 'setComments',
        'currency_code' => 'setCurrencyCode',
        'customs_declared_value' => 'setCustomsDeclaredValue',
        'declaration_statement' => 'setDeclarationStatement',
        'freight_charge' => 'setFreightCharge',
        'from_customs_reference' => 'setFromCustomsReference',
        'handling_costs' => 'setHandlingCosts',
        'importer_customs_reference' => 'setImporterCustomsReference',
        'insured_amount' => 'setInsuredAmount',
        'insured_number' => 'setInsuredNumber',
        'invoice_number' => 'setInvoiceNumber',
        'license_number' => 'setLicenseNumber',
        'other_charge' => 'setOtherCharge',
        'other_description' => 'setOtherDescription',
        'other_type' => 'setOtherType',
        'packing_costs' => 'setPackingCosts',
        'producer_specification' => 'setProducerSpecification',
        'reason_for_export' => 'setReasonForExport',
        'reason_for_export_explanation' => 'setReasonForExportExplanation',
        'sdr_value' => 'setSdrValue',
        'shipping_document_type' => 'setShippingDocumentType',
        'signature_contact' => 'setSignatureContact',
        'terms_of_sale' => 'setTermsOfSale'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'eelpfc' => 'getEelpfc',
        'blanket_end_date' => 'getBlanketEndDate',
        'blanket_start_date' => 'getBlanketStartDate',
        'certificate_number' => 'getCertificateNumber',
        'comments' => 'getComments',
        'currency_code' => 'getCurrencyCode',
        'customs_declared_value' => 'getCustomsDeclaredValue',
        'declaration_statement' => 'getDeclarationStatement',
        'freight_charge' => 'getFreightCharge',
        'from_customs_reference' => 'getFromCustomsReference',
        'handling_costs' => 'getHandlingCosts',
        'importer_customs_reference' => 'getImporterCustomsReference',
        'insured_amount' => 'getInsuredAmount',
        'insured_number' => 'getInsuredNumber',
        'invoice_number' => 'getInvoiceNumber',
        'license_number' => 'getLicenseNumber',
        'other_charge' => 'getOtherCharge',
        'other_description' => 'getOtherDescription',
        'other_type' => 'getOtherType',
        'packing_costs' => 'getPackingCosts',
        'producer_specification' => 'getProducerSpecification',
        'reason_for_export' => 'getReasonForExport',
        'reason_for_export_explanation' => 'getReasonForExportExplanation',
        'sdr_value' => 'getSdrValue',
        'shipping_document_type' => 'getShippingDocumentType',
        'signature_contact' => 'getSignatureContact',
        'terms_of_sale' => 'getTermsOfSale'
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

    const OTHER_TYPE_COMMISSIONS = 'COMMISSIONS';
    const OTHER_TYPE_DISCOUNTS = 'DISCOUNTS';
    const OTHER_TYPE_HANDLING_FEES = 'HANDLING_FEES';
    const OTHER_TYPE_OTHER = 'OTHER';
    const OTHER_TYPE_ROYALTIES_AND_LICENSE_FEES = 'ROYALTIES_AND_LICENSE_FEES';
    const OTHER_TYPE_TAXES = 'TAXES';
    const PRODUCER_SPECIFICATION_MULTIPLE_SPECIFIED = 'MULTIPLE_SPECIFIED';
    const PRODUCER_SPECIFICATION_SAME = 'SAME';
    const PRODUCER_SPECIFICATION_SINGLE_SPECIFIED = 'SINGLE_SPECIFIED';
    const PRODUCER_SPECIFICATION_UNKNOWN = 'UNKNOWN';
    const PRODUCER_SPECIFICATION_AVAILABLE_UPON_REQUEST = 'AVAILABLE_UPON_REQUEST';
    const REASON_FOR_EXPORT_GIFT = 'GIFT';
    const REASON_FOR_EXPORT_COMMERCIAL_SAMPLE = 'COMMERCIAL_SAMPLE';
    const REASON_FOR_EXPORT_MERCHANDISE = 'MERCHANDISE';
    const REASON_FOR_EXPORT_DOCUMENTS = 'DOCUMENTS';
    const REASON_FOR_EXPORT_RETURNED_GOODS = 'RETURNED_GOODS';
    const REASON_FOR_EXPORT_SOLD = 'SOLD';
    const REASON_FOR_EXPORT_NOT_SOLD = 'NOT_SOLD';
    const REASON_FOR_EXPORT_OTHER = 'OTHER';
    const REASON_FOR_EXPORT_DANGEROUS_GOOD = 'DANGEROUS_GOOD';
    const REASON_FOR_EXPORT_HUMANITARIAN_GOODS = 'HUMANITARIAN_GOODS';
    const SHIPPING_DOCUMENT_TYPE_NAFTA = 'NAFTA';
    const SHIPPING_DOCUMENT_TYPE_COO = 'COO';



    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getOtherTypeAllowableValues()
    {
        return [
            self::OTHER_TYPE_COMMISSIONS,
            self::OTHER_TYPE_DISCOUNTS,
            self::OTHER_TYPE_HANDLING_FEES,
            self::OTHER_TYPE_OTHER,
            self::OTHER_TYPE_ROYALTIES_AND_LICENSE_FEES,
            self::OTHER_TYPE_TAXES,
        ];
    }

    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getProducerSpecificationAllowableValues()
    {
        return [
            self::PRODUCER_SPECIFICATION_MULTIPLE_SPECIFIED,
            self::PRODUCER_SPECIFICATION_SAME,
            self::PRODUCER_SPECIFICATION_SINGLE_SPECIFIED,
            self::PRODUCER_SPECIFICATION_UNKNOWN,
            self::PRODUCER_SPECIFICATION_AVAILABLE_UPON_REQUEST,
        ];
    }

    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getReasonForExportAllowableValues()
    {
        return [
            self::REASON_FOR_EXPORT_GIFT,
            self::REASON_FOR_EXPORT_COMMERCIAL_SAMPLE,
            self::REASON_FOR_EXPORT_MERCHANDISE,
            self::REASON_FOR_EXPORT_DOCUMENTS,
            self::REASON_FOR_EXPORT_RETURNED_GOODS,
            self::REASON_FOR_EXPORT_SOLD,
            self::REASON_FOR_EXPORT_NOT_SOLD,
            self::REASON_FOR_EXPORT_OTHER,
            self::REASON_FOR_EXPORT_DANGEROUS_GOOD,
            self::REASON_FOR_EXPORT_HUMANITARIAN_GOODS,
        ];
    }

    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getShippingDocumentTypeAllowableValues()
    {
        return [
            self::SHIPPING_DOCUMENT_TYPE_NAFTA,
            self::SHIPPING_DOCUMENT_TYPE_COO,
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
        $this->container['eelpfc'] = isset($data['eelpfc']) ? $data['eelpfc'] : null;
        $this->container['blanket_end_date'] = isset($data['blanket_end_date']) ? $data['blanket_end_date'] : null;
        $this->container['blanket_start_date'] = isset($data['blanket_start_date']) ? $data['blanket_start_date'] : null;
        $this->container['certificate_number'] = isset($data['certificate_number']) ? $data['certificate_number'] : null;
        $this->container['comments'] = isset($data['comments']) ? $data['comments'] : null;
        $this->container['currency_code'] = isset($data['currency_code']) ? $data['currency_code'] : null;
        $this->container['customs_declared_value'] = isset($data['customs_declared_value']) ? $data['customs_declared_value'] : null;
        $this->container['declaration_statement'] = isset($data['declaration_statement']) ? $data['declaration_statement'] : null;
        $this->container['freight_charge'] = isset($data['freight_charge']) ? $data['freight_charge'] : null;
        $this->container['from_customs_reference'] = isset($data['from_customs_reference']) ? $data['from_customs_reference'] : null;
        $this->container['handling_costs'] = isset($data['handling_costs']) ? $data['handling_costs'] : null;
        $this->container['importer_customs_reference'] = isset($data['importer_customs_reference']) ? $data['importer_customs_reference'] : null;
        $this->container['insured_amount'] = isset($data['insured_amount']) ? $data['insured_amount'] : null;
        $this->container['insured_number'] = isset($data['insured_number']) ? $data['insured_number'] : null;
        $this->container['invoice_number'] = isset($data['invoice_number']) ? $data['invoice_number'] : null;
        $this->container['license_number'] = isset($data['license_number']) ? $data['license_number'] : null;
        $this->container['other_charge'] = isset($data['other_charge']) ? $data['other_charge'] : null;
        $this->container['other_description'] = isset($data['other_description']) ? $data['other_description'] : null;
        $this->container['other_type'] = isset($data['other_type']) ? $data['other_type'] : null;
        $this->container['packing_costs'] = isset($data['packing_costs']) ? $data['packing_costs'] : null;
        $this->container['producer_specification'] = isset($data['producer_specification']) ? $data['producer_specification'] : null;
        $this->container['reason_for_export'] = isset($data['reason_for_export']) ? $data['reason_for_export'] : null;
        $this->container['reason_for_export_explanation'] = isset($data['reason_for_export_explanation']) ? $data['reason_for_export_explanation'] : null;
        $this->container['sdr_value'] = isset($data['sdr_value']) ? $data['sdr_value'] : null;
        $this->container['shipping_document_type'] = isset($data['shipping_document_type']) ? $data['shipping_document_type'] : null;
        $this->container['signature_contact'] = isset($data['signature_contact']) ? $data['signature_contact'] : null;
        $this->container['terms_of_sale'] = isset($data['terms_of_sale']) ? $data['terms_of_sale'] : null;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];

        if ($this->container['currency_code'] === null) {
            $invalidProperties[] = "'currency_code' can't be null";
        }
        $allowedValues = $this->getOtherTypeAllowableValues();
        if (!is_null($this->container['other_type']) && !in_array($this->container['other_type'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value for 'other_type', must be one of '%s'",
                implode("', '", $allowedValues)
            );
        }

        $allowedValues = $this->getProducerSpecificationAllowableValues();
        if (!is_null($this->container['producer_specification']) && !in_array($this->container['producer_specification'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value for 'producer_specification', must be one of '%s'",
                implode("', '", $allowedValues)
            );
        }

        $allowedValues = $this->getReasonForExportAllowableValues();
        if (!is_null($this->container['reason_for_export']) && !in_array($this->container['reason_for_export'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value for 'reason_for_export', must be one of '%s'",
                implode("', '", $allowedValues)
            );
        }

        $allowedValues = $this->getShippingDocumentTypeAllowableValues();
        if (!is_null($this->container['shipping_document_type']) && !in_array($this->container['shipping_document_type'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value for 'shipping_document_type', must be one of '%s'",
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
     * Gets eelpfc
     *
     * @return string|null
     */
    public function getEelpfc()
    {
        return $this->container['eelpfc'];
    }

    /**
     * Sets eelpfc
     *
     * @param string|null $eelpfc eelpfc
     *
     * @return $this
     */
    public function setEelpfc($eelpfc)
    {
        $this->container['eelpfc'] = $eelpfc;

        return $this;
    }

    /**
     * Gets blanket_end_date
     *
     * @return string|null
     */
    public function getBlanketEndDate()
    {
        return $this->container['blanket_end_date'];
    }

    /**
     * Sets blanket_end_date
     *
     * @param string|null $blanket_end_date format: YYYY-MM-DD
     *
     * @return $this
     */
    public function setBlanketEndDate($blanket_end_date)
    {
        $this->container['blanket_end_date'] = $blanket_end_date;

        return $this;
    }

    /**
     * Gets blanket_start_date
     *
     * @return string|null
     */
    public function getBlanketStartDate()
    {
        return $this->container['blanket_start_date'];
    }

    /**
     * Sets blanket_start_date
     *
     * @param string|null $blanket_start_date format: YYYY-MM-DD
     *
     * @return $this
     */
    public function setBlanketStartDate($blanket_start_date)
    {
        $this->container['blanket_start_date'] = $blanket_start_date;

        return $this;
    }

    /**
     * Gets certificate_number
     *
     * @return string|null
     */
    public function getCertificateNumber()
    {
        return $this->container['certificate_number'];
    }

    /**
     * Sets certificate_number
     *
     * @param string|null $certificate_number certificate_number
     *
     * @return $this
     */
    public function setCertificateNumber($certificate_number)
    {
        $this->container['certificate_number'] = $certificate_number;

        return $this;
    }

    /**
     * Gets comments
     *
     * @return string|null
     */
    public function getComments()
    {
        return $this->container['comments'];
    }

    /**
     * Sets comments
     *
     * @param string|null $comments comments
     *
     * @return $this
     */
    public function setComments($comments)
    {
        $this->container['comments'] = $comments;

        return $this;
    }

    /**
     * Gets currency_code
     *
     * @return string
     */
    public function getCurrencyCode()
    {
        return $this->container['currency_code'];
    }

    /**
     * Sets currency_code
     *
     * @param string $currency_code ISO-4217
     *
     * @return $this
     */
    public function setCurrencyCode($currency_code)
    {
        $this->container['currency_code'] = $currency_code;

        return $this;
    }

    /**
     * Gets customs_declared_value
     *
     * @return float|null
     */
    public function getCustomsDeclaredValue()
    {
        return $this->container['customs_declared_value'];
    }

    /**
     * Sets customs_declared_value
     *
     * @param float|null $customs_declared_value customs_declared_value
     *
     * @return $this
     */
    public function setCustomsDeclaredValue($customs_declared_value)
    {
        $this->container['customs_declared_value'] = $customs_declared_value;

        return $this;
    }

    /**
     * Gets declaration_statement
     *
     * @return string|null
     */
    public function getDeclarationStatement()
    {
        return $this->container['declaration_statement'];
    }

    /**
     * Sets declaration_statement
     *
     * @param string|null $declaration_statement declaration_statement
     *
     * @return $this
     */
    public function setDeclarationStatement($declaration_statement)
    {
        $this->container['declaration_statement'] = $declaration_statement;

        return $this;
    }

    /**
     * Gets freight_charge
     *
     * @return float|null
     */
    public function getFreightCharge()
    {
        return $this->container['freight_charge'];
    }

    /**
     * Sets freight_charge
     *
     * @param float|null $freight_charge freight_charge
     *
     * @return $this
     */
    public function setFreightCharge($freight_charge)
    {
        $this->container['freight_charge'] = $freight_charge;

        return $this;
    }

    /**
     * Gets from_customs_reference
     *
     * @return string|null
     */
    public function getFromCustomsReference()
    {
        return $this->container['from_customs_reference'];
    }

    /**
     * Sets from_customs_reference
     *
     * @param string|null $from_customs_reference from_customs_reference
     *
     * @return $this
     */
    public function setFromCustomsReference($from_customs_reference)
    {
        $this->container['from_customs_reference'] = $from_customs_reference;

        return $this;
    }

    /**
     * Gets handling_costs
     *
     * @return float|null
     */
    public function getHandlingCosts()
    {
        return $this->container['handling_costs'];
    }

    /**
     * Sets handling_costs
     *
     * @param float|null $handling_costs handling_costs
     *
     * @return $this
     */
    public function setHandlingCosts($handling_costs)
    {
        $this->container['handling_costs'] = $handling_costs;

        return $this;
    }

    /**
     * Gets importer_customs_reference
     *
     * @return string|null
     */
    public function getImporterCustomsReference()
    {
        return $this->container['importer_customs_reference'];
    }

    /**
     * Sets importer_customs_reference
     *
     * @param string|null $importer_customs_reference importer_customs_reference
     *
     * @return $this
     */
    public function setImporterCustomsReference($importer_customs_reference)
    {
        $this->container['importer_customs_reference'] = $importer_customs_reference;

        return $this;
    }

    /**
     * Gets insured_amount
     *
     * @return float|null
     */
    public function getInsuredAmount()
    {
        return $this->container['insured_amount'];
    }

    /**
     * Sets insured_amount
     *
     * @param float|null $insured_amount insured_amount
     *
     * @return $this
     */
    public function setInsuredAmount($insured_amount)
    {
        $this->container['insured_amount'] = $insured_amount;

        return $this;
    }

    /**
     * Gets insured_number
     *
     * @return string|null
     */
    public function getInsuredNumber()
    {
        return $this->container['insured_number'];
    }

    /**
     * Sets insured_number
     *
     * @param string|null $insured_number insured_number
     *
     * @return $this
     */
    public function setInsuredNumber($insured_number)
    {
        $this->container['insured_number'] = $insured_number;

        return $this;
    }

    /**
     * Gets invoice_number
     *
     * @return string|null
     */
    public function getInvoiceNumber()
    {
        return $this->container['invoice_number'];
    }

    /**
     * Sets invoice_number
     *
     * @param string|null $invoice_number invoice_number
     *
     * @return $this
     */
    public function setInvoiceNumber($invoice_number)
    {
        $this->container['invoice_number'] = $invoice_number;

        return $this;
    }

    /**
     * Gets license_number
     *
     * @return string|null
     */
    public function getLicenseNumber()
    {
        return $this->container['license_number'];
    }

    /**
     * Sets license_number
     *
     * @param string|null $license_number license_number
     *
     * @return $this
     */
    public function setLicenseNumber($license_number)
    {
        $this->container['license_number'] = $license_number;

        return $this;
    }

    /**
     * Gets other_charge
     *
     * @return float|null
     */
    public function getOtherCharge()
    {
        return $this->container['other_charge'];
    }

    /**
     * Sets other_charge
     *
     * @param float|null $other_charge other_charge
     *
     * @return $this
     */
    public function setOtherCharge($other_charge)
    {
        $this->container['other_charge'] = $other_charge;

        return $this;
    }

    /**
     * Gets other_description
     *
     * @return string|null
     */
    public function getOtherDescription()
    {
        return $this->container['other_description'];
    }

    /**
     * Sets other_description
     *
     * @param string|null $other_description other_description
     *
     * @return $this
     */
    public function setOtherDescription($other_description)
    {
        $this->container['other_description'] = $other_description;

        return $this;
    }

    /**
     * Gets other_type
     *
     * @return string|null
     */
    public function getOtherType()
    {
        return $this->container['other_type'];
    }

    /**
     * Sets other_type
     *
     * @param string|null $other_type other_type
     *
     * @return $this
     */
    public function setOtherType($other_type)
    {
        $allowedValues = $this->getOtherTypeAllowableValues();
        if (!is_null($other_type) && !in_array($other_type, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value for 'other_type', must be one of '%s'",
                    implode("', '", $allowedValues)
                )
            );
        }
        $this->container['other_type'] = $other_type;

        return $this;
    }

    /**
     * Gets packing_costs
     *
     * @return float|null
     */
    public function getPackingCosts()
    {
        return $this->container['packing_costs'];
    }

    /**
     * Sets packing_costs
     *
     * @param float|null $packing_costs packing_costs
     *
     * @return $this
     */
    public function setPackingCosts($packing_costs)
    {
        $this->container['packing_costs'] = $packing_costs;

        return $this;
    }

    /**
     * Gets producer_specification
     *
     * @return string|null
     */
    public function getProducerSpecification()
    {
        return $this->container['producer_specification'];
    }

    /**
     * Sets producer_specification
     *
     * @param string|null $producer_specification producer_specification
     *
     * @return $this
     */
    public function setProducerSpecification($producer_specification)
    {
        $allowedValues = $this->getProducerSpecificationAllowableValues();
        if (!is_null($producer_specification) && !in_array($producer_specification, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value for 'producer_specification', must be one of '%s'",
                    implode("', '", $allowedValues)
                )
            );
        }
        $this->container['producer_specification'] = $producer_specification;

        return $this;
    }

    /**
     * Gets reason_for_export
     *
     * @return string|null
     */
    public function getReasonForExport()
    {
        return $this->container['reason_for_export'];
    }

    /**
     * Sets reason_for_export
     *
     * @param string|null $reason_for_export reason_for_export
     *
     * @return $this
     */
    public function setReasonForExport($reason_for_export)
    {
        $allowedValues = $this->getReasonForExportAllowableValues();
        if (!is_null($reason_for_export) && !in_array($reason_for_export, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value for 'reason_for_export', must be one of '%s'",
                    implode("', '", $allowedValues)
                )
            );
        }
        $this->container['reason_for_export'] = $reason_for_export;

        return $this;
    }

    /**
     * Gets reason_for_export_explanation
     *
     * @return string|null
     */
    public function getReasonForExportExplanation()
    {
        return $this->container['reason_for_export_explanation'];
    }

    /**
     * Sets reason_for_export_explanation
     *
     * @param string|null $reason_for_export_explanation reason_for_export_explanation
     *
     * @return $this
     */
    public function setReasonForExportExplanation($reason_for_export_explanation)
    {
        $this->container['reason_for_export_explanation'] = $reason_for_export_explanation;

        return $this;
    }

    /**
     * Gets sdr_value
     *
     * @return float|null
     */
    public function getSdrValue()
    {
        return $this->container['sdr_value'];
    }

    /**
     * Sets sdr_value
     *
     * @param float|null $sdr_value sdr_value
     *
     * @return $this
     */
    public function setSdrValue($sdr_value)
    {
        $this->container['sdr_value'] = $sdr_value;

        return $this;
    }

    /**
     * Gets shipping_document_type
     *
     * @return string|null
     */
    public function getShippingDocumentType()
    {
        return $this->container['shipping_document_type'];
    }

    /**
     * Sets shipping_document_type
     *
     * @param string|null $shipping_document_type shipping_document_type
     *
     * @return $this
     */
    public function setShippingDocumentType($shipping_document_type)
    {
        $allowedValues = $this->getShippingDocumentTypeAllowableValues();
        if (!is_null($shipping_document_type) && !in_array($shipping_document_type, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value for 'shipping_document_type', must be one of '%s'",
                    implode("', '", $allowedValues)
                )
            );
        }
        $this->container['shipping_document_type'] = $shipping_document_type;

        return $this;
    }

    /**
     * Gets signature_contact
     *
     * @return \pitneybowesShipping\shippingApi\model\Address|null
     */
    public function getSignatureContact()
    {
        return $this->container['signature_contact'];
    }

    /**
     * Sets signature_contact
     *
     * @param \pitneybowesShipping\shippingApi\model\Address|null $signature_contact signature_contact
     *
     * @return $this
     */
    public function setSignatureContact($signature_contact)
    {
        $this->container['signature_contact'] = $signature_contact;

        return $this;
    }

    /**
     * Gets terms_of_sale
     *
     * @return string|null
     */
    public function getTermsOfSale()
    {
        return $this->container['terms_of_sale'];
    }

    /**
     * Sets terms_of_sale
     *
     * @param string|null $terms_of_sale terms_of_sale
     *
     * @return $this
     */
    public function setTermsOfSale($terms_of_sale)
    {
        $this->container['terms_of_sale'] = $terms_of_sale;

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

