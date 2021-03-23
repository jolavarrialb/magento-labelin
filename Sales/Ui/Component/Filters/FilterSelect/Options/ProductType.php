<?php

declare(strict_types=1);

namespace Labelin\Sales\Ui\Component\Filters\FilterSelect\Options;

use Magento\Framework\Data\OptionSourceInterface;

class ProductType implements OptionSourceInterface
{

    public function toOptionArray()
    {
        return [
            [
                'value' => 'configurable',
                'label' => __('Artwork'),
            ],
            [
                'value' => 'simple',
                'label' => __('Pre-made'),
            ],

        ];
    }
}
