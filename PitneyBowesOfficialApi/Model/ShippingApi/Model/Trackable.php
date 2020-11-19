<?php
/**
 * Trackable
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
use \Labelin\PitneyBowesOfficialApi\Model\ObjectSerializer;

/**
 * Trackable Class Doc Comment
 *
 * @category Class
 * @description * TRACKABLE - Item is trackable by default. * NON_TRACKABLE - Item is not trackable. * REQUIRES_TRACKABLE_SPECIAL_SERVICE - Item is trackable if special service is requested.
 * @package  pitneybowesShipping
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */
class Trackable
{
    /**
     * Possible values of this enum
     */
    const TRACKABLE = 'TRACKABLE';
    const NON_TRACKABLE = 'NON_TRACKABLE';
    const REQUIRES_TRACKABLE_SPECIAL_SERVICE = 'REQUIRES_TRACKABLE_SPECIAL_SERVICE';

    /**
     * Gets allowable values of the enum
     * @return string[]
     */
    public static function getAllowableEnumValues()
    {
        return [
            self::TRACKABLE,
            self::NON_TRACKABLE,
            self::REQUIRES_TRACKABLE_SPECIAL_SERVICE,
        ];
    }
}

