<?php
/**
 * ParcelType
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
 * ParcelType Class Doc Comment
 *
 * @category Class
 * @description * LETTER -  Letter. Generates a First-Class label with IMB barcode.  * FRE - Flat rate envelope. * LGENV - Legal flat rate envelope. * LGLFRENV - Padded flat rate envelope. * PFRENV - Small flat rate box. * SFRB - Medium flat rate box. * FRB - Large Flat rate box. * LFRB - DVD box. * DVDBOX - DVDBOX. * VIDEOBOX - Video box. * MLFRB - Military flat raqte box. * RBA - Regional rate box, type A * RBB -  Regional rate box, type B. * PKG - Package (not eligible for special package rate). * LP - Large package. * FLAT - USPS Flat or Large Envelope. * EMMTB - Extended Managed Mail Tray Box. * FTB - Full tray box. * HTB - Half tray box. * SACK - Sack. * FTTB - Flat tub tray. * SOFTPACK - Soft Pack Envelope. * MIX - PMOD Enclosed Package Type. * LTR - Letter for stamp API call.
 * @package  pitneybowesShipping
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */
class ParcelType
{
    /**
     * Possible values of this enum
     */
    const FLAT = 'FLAT';
    const LETTER = 'LETTER';
    const FRE = 'FRE';
    const LGENV = 'LGENV';
    const LGLFRENV = 'LGLFRENV';
    const PFRENV = 'PFRENV';
    const FRB = 'FRB';
    const LFRB = 'LFRB';
    const DVDBOX = 'DVDBOX';
    const VIDEOBOX = 'VIDEOBOX';
    const MLFRB = 'MLFRB';
    const RBA = 'RBA';
    const RBB = 'RBB';
    const LP = 'LP';
    const SACK = 'SACK';
    const SOFTPACK = 'SOFTPACK';
    const MIX = 'MIX';
    const LTR = 'LTR';
    const NMLETTER = 'NMLETTER';
    const NMLTR = 'NMLTR';
    const IRRPKG = 'IRRPKG';
    const SFRB = 'SFRB';
    const EMMTB = 'EMMTB';
    const FTB = 'FTB';
    const FTTB = 'FTTB';
    const HTB = 'HTB';
    const PACK = 'PACK';
    const BOX = 'BOX';
    const SMALL_EXP_BOX = 'SMALL_EXP_BOX';
    const MED_EXP_BOX = 'MED_EXP_BOX';
    const LG_EXP_BOX = 'LG_EXP_BOX';
    const EXTRA_LG_EXP_BOX = 'EXTRA_LG_EXP_BOX';
    const TUBE = 'TUBE';
    const _25_KG = '25KG';
    const _10_KG = '10KG';
    const PKG = 'PKG';

    /**
     * Gets allowable values of the enum
     * @return string[]
     */
    public static function getAllowableEnumValues()
    {
        return [
            self::FLAT,
            self::LETTER,
            self::FRE,
            self::LGENV,
            self::LGLFRENV,
            self::PFRENV,
            self::FRB,
            self::LFRB,
            self::DVDBOX,
            self::VIDEOBOX,
            self::MLFRB,
            self::RBA,
            self::RBB,
            self::LP,
            self::SACK,
            self::SOFTPACK,
            self::MIX,
            self::LTR,
            self::NMLETTER,
            self::NMLTR,
            self::IRRPKG,
            self::SFRB,
            self::EMMTB,
            self::FTB,
            self::FTTB,
            self::HTB,
            self::PACK,
            self::BOX,
            self::SMALL_EXP_BOX,
            self::MED_EXP_BOX,
            self::LG_EXP_BOX,
            self::EXTRA_LG_EXP_BOX,
            self::TUBE,
            self::_25_KG,
            self::_10_KG,
            self::PKG,
        ];
    }
}


