<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Ui\Component\Column\Options;

use Magento\Framework\Data\OptionSourceInterface;

class Status implements OptionSourceInterface
{
    /** @var array */
    protected $options;

    public function toOptionArray(): array
    {
        if ($this->options) {
            return $this->options;
        }

        $this->options = [
            [
                'value' => 0,
                'label' => __('Not completed'),
            ],
            [
                'value' => 1,
                'label' => __('Completed'),
            ],
        ];

        return $this->options;
    }
}
