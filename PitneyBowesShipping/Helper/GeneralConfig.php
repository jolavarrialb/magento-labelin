<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesShipping\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

class GeneralConfig extends AbstractHelper
{
    public const CONTAINER_PKG = 'PKG';

    public const CONTAINER_PRIORITY_FLAT_RATE_ENVELOPE = 'FLAT_RATE_ENV';
    public const CONTAINER_PRIORITY_LARGE_FLAT_RATE_BOX = 'LG_FLAT_RATE_BOX';
    public const CONTAINER_PRIORITY_MEDIUM_FLAT_RATE_BOX = 'MED_FLAT_RATE_BOX';

    public const CONTAINER_FIRST_CLASS_THICK_ENVELOP_PKG = 'FIRST_CLASS_ENVELOPE_PKG';

    protected const PACKS_TYPES = [
        self::CONTAINER_PKG => [
            'dimensionRules' => [
                'required' => true,
            ],
            'weightRules' => [
                'required' => true,
            ],
            'suggestedTrackableSpecialServiceId' => null,
        ],
        self::CONTAINER_PRIORITY_FLAT_RATE_ENVELOPE => [
            'weightRules' => [
                'required' => true,
                'unitOfMeasurement' => 'OZ',
                'minWeight' => 0.01,
                'maxWeight' => 1120.0,
            ],
            'dimensionRules' => [
                'required' => false,
                'unitOfMeasurement' => 'IN',
                'minParcelDimensions' => [
                    'length' => 0.0,
                    'width' => 0.0,
                    'height' => 0.0,
                    'unitOfMeasurement' => 'IN',
                ],
                'maxParcelDimensions' => [
                    'length' => 8.69,
                    'width' => 1.75,
                    'height' => 5.44,
                    'unitOfMeasurement' => 'IN',
                ],
            ],
            'suggestedTrackableSpecialServiceId' => 'DelCon',
        ],
        self::CONTAINER_PRIORITY_LARGE_FLAT_RATE_BOX => [
            'weightRules' => [
                'required' => true,
                'unitOfMeasurement' => 'OZ',
                'minWeight' => 0.01,
                'maxWeight' => 1120.0,
            ],
            'dimensionRules' => [
                'required' => false,
                'unitOfMeasurement' => 'IN',
                'minParcelDimensions' => [
                    'length' => 0.0,
                    'width' => 0.0,
                    'height' => 0.0,
                    'unitOfMeasurement' => 'IN',
                ],
                'maxParcelDimensions' => [
                    'length' => 12.25,
                    'width' => 6.0,
                    'height' => 12.25,
                    'unitOfMeasurement' => 'IN',
                ],
            ],
            'suggestedTrackableSpecialServiceId' => 'DelCon',
        ],
        self::CONTAINER_PRIORITY_MEDIUM_FLAT_RATE_BOX => [
            'weightRules' => [
                'required' => true,
                'unitOfMeasurement' => 'OZ',
                'minWeight' => 0.01,
                'maxWeight' => 1120.0,
            ],
            'dimensionRules' => [
                'required' => false,
                'unitOfMeasurement' => 'IN',
                'minParcelDimensions' => [
                    'length' => 0.0,
                    'width' => 0.0,
                    'height' => 0.0,
                    'unitOfMeasurement' => 'IN',
                ],
                'maxParcelDimensions' => [
                    'length' => 14,
                    'width' => 3.5,
                    'height' => 12.0,
                    'unitOfMeasurement' => 'IN',
                ],
            ],
            'suggestedTrackableSpecialServiceId' => 'DelCon',
        ],
        self::CONTAINER_FIRST_CLASS_THICK_ENVELOP_PKG => [
            'weightRules' => [
                'required' => true,
                'unitOfMeasurement' => 'OZ',
                'minWeight' => 0.01,
                'maxWeight' => 15.99,
            ],
            'dimensionRules' => [
                'required' => true,
                'unitOfMeasurement' => 'IN',
                'minParcelDimensions' => [
                    'length' => 0.001,
                    'width' => 0.001,
                    'height' => 0.001,
                    'unitOfMeasurement' => 'IN',
                ],
                'maxParcelDimensions' => [
                    'length' => 22,
                    'width' => 15,
                    'height' => 18,
                    'unitOfMeasurement' => 'IN',
                ],
            ],
            'suggestedTrackableSpecialServiceId' => 'DelCon',
        ],
    ];

    public function getCode(string $type): array
    {
        $codes = $this->getCodes();

        if (!isset($codes[$type])) {
            return [];
        }

        return $codes[$type];
    }

    protected function getCodes(): array
    {
        return [
            'container' => [
                static::CONTAINER_PKG => static::CONTAINER_PKG,
                static::CONTAINER_PRIORITY_FLAT_RATE_ENVELOPE => [
                    'code' => static::CONTAINER_PRIORITY_FLAT_RATE_ENVELOPE,
                ],
                static::CONTAINER_PRIORITY_LARGE_FLAT_RATE_BOX => [
                    'code' => static::CONTAINER_PRIORITY_LARGE_FLAT_RATE_BOX,
                ],
                static::CONTAINER_PRIORITY_MEDIUM_FLAT_RATE_BOX => [
                    'code' => static::CONTAINER_PRIORITY_MEDIUM_FLAT_RATE_BOX,
                ],
                static::CONTAINER_FIRST_CLASS_THICK_ENVELOP_PKG => [
                    'code' => static::CONTAINER_FIRST_CLASS_THICK_ENVELOP_PKG,
                ],
            ],
            'container_description' => [
                static::CONTAINER_PKG => __('Customer Packaging'),
                static::CONTAINER_FIRST_CLASS_THICK_ENVELOP_PKG => __('First-Class Mail Package'),
                static::CONTAINER_PRIORITY_MEDIUM_FLAT_RATE_BOX => __('Medium Flat Rate Box'),
                static::CONTAINER_PRIORITY_LARGE_FLAT_RATE_BOX => __('Large Flat Rate Box'),
                static::CONTAINER_PRIORITY_FLAT_RATE_ENVELOPE => __('Flat Rate Envelope Box'),
            ],
            'packagesTypes' => static::PACKS_TYPES,
            'packagesServices' => [
                static::CONTAINER_PKG => [
                    'serviceId' => null,
                    'parcelType' => 'PKG',
                ],
                static::CONTAINER_PRIORITY_MEDIUM_FLAT_RATE_BOX => [
                    'serviceId' => 'PM',
                    'parcelType' => 'FRB',
                ],
                static::CONTAINER_PRIORITY_FLAT_RATE_ENVELOPE => [
                    'serviceId' => 'PM',
                    'parcelType' => 'SFRB',
                ],
                static::CONTAINER_PRIORITY_LARGE_FLAT_RATE_BOX => [
                    'serviceId' => 'PM',
                    'parcelType' => 'LFRB',
                ],
                static::CONTAINER_FIRST_CLASS_THICK_ENVELOP_PKG => [
                    'serviceId' => 'FCM',
                    'parcelType' => 'PKG',
                ],
            ],
            'unit_of_measure' => [
                'LB' => __('Pounds'),
                'OZ' => __('Ounces'),
            ],
            'method' => [
                'USPS' => __('USPS'),
                'UPS' => __('UPS'),
            ],
        ];
    }
}
