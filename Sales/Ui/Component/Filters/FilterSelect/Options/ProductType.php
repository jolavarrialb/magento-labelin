<?php

declare(strict_types=1);

namespace Labelin\Sales\Ui\Component\Filters\FilterSelect\Options;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Catalog\Model\Product\Type;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;

class ProductType implements OptionSourceInterface
{
    public function toOptionArray(): array
    {
        return [
            [
                'value' => Configurable::TYPE_CODE,
                'label' => __('Artwork'),
            ],
            [
                'value' => Type::TYPE_SIMPLE,
                'label' => __('Pre-made'),
            ],
            [
                'value' => 'is_reordered',
                'label' => __('Reorder'),
            ],

        ];
    }
}
