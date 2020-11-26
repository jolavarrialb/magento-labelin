<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesShipping\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

class GeneralConfig extends AbstractHelper
{
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
                'PKG' => '00',
            ],
            'container_description' => [
                'PKG' => __('Customer Packaging'),
            ],
            'unit_of_measure' => [
                'LB' => __('Pounds'),
                'KGS' => __('Kilograms'),
                'OZ' => __('Ounces'),
            ],
            'method' => [
                'USPS' => __('USPS'),
                'UPS' => __('UPS'),
            ],
        ];
    }
}
