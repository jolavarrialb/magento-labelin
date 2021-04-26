<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesShipping\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

class GeneralConfig extends AbstractHelper
{
    public const CONTAINER_PKG = 'PKG';
    public const CONTAINER_SMALL_EXP_BOX = 'SMALL_EXP_BOX';
    public const CONTAINER_MED_EXP_BOX = 'MED_EXP_BOX';
    public const CONTAINER_LG_EXP_BOX = 'LG_EXP_BOX';

    protected const CONTAINER_SIZES = [
        self::CONTAINER_SMALL_EXP_BOX => [
            'length' => 13,
            'width' => 11,
            'height' => 2,
        ],
        self::CONTAINER_MED_EXP_BOX => [
            'length' => 16,
            'width' => 11,
            'height' => 3,
        ],
        self::CONTAINER_LG_EXP_BOX => [
            'length' => 18,
            'width' => 13,
            'height' => 3,
        ],
    ];

    protected const CONTAINER_CODES = [
        self::CONTAINER_PKG => '00',
        self::CONTAINER_SMALL_EXP_BOX => '2a',
        self::CONTAINER_MED_EXP_BOX => '2b',
        self::CONTAINER_LG_EXP_BOX => '2c',
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
                static::CONTAINER_PKG => $this->getContainerCode(static::CONTAINER_PKG),
                static::CONTAINER_SMALL_EXP_BOX => [
                    'code' => $this->getContainerCode(static::CONTAINER_SMALL_EXP_BOX),
                    'sizes' => $this->getContainerSizes(static::CONTAINER_SMALL_EXP_BOX),
                ],
                static::CONTAINER_MED_EXP_BOX => [
                    'code' => $this->getContainerCode(static::CONTAINER_MED_EXP_BOX),
                    'sizes' => $this->getContainerSizes(static::CONTAINER_MED_EXP_BOX),
                ],
                static::CONTAINER_LG_EXP_BOX => [
                    'code' => $this->getContainerCode(static::CONTAINER_LG_EXP_BOX),
                    'sizes' => $this->getContainerSizes(static::CONTAINER_LG_EXP_BOX),
                ],
            ],
            'container_description' => [
                static::CONTAINER_PKG => __('Customer Packaging'),
                static::CONTAINER_SMALL_EXP_BOX => __('Box Small'),
                static::CONTAINER_MED_EXP_BOX => __('Box Medium'),
                static::CONTAINER_LG_EXP_BOX => __('Box Large'),
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

    protected function getContainerSizes($key): array
    {
        return static::CONTAINER_SIZES[$key];
    }

    protected function getContainerCode($key): string
    {
        return static::CONTAINER_CODES[$key];
    }

    public function getSizes($containerKey): array
    {
        if (array_key_exists($containerKey, static::CONTAINER_SIZES)) {
            return static::CONTAINER_SIZES[$containerKey];
        }

        return [];
    }
}
