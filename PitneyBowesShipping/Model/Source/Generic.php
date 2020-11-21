<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesShipping\Model\Source;

use Labelin\PitneyBowesShipping\Helper\Config;
use Magento\Framework\Data\OptionSourceInterface;

class Generic implements OptionSourceInterface
{
    /** @var Config */
    protected $carrierConfig;

    /** @var string */
    protected $code = '';

    /** @var array */
    protected $options = [];

    public function __construct(Config $carrierConfig)
    {
        $this->carrierConfig = $carrierConfig;
    }

    public function toOptionArray(): array
    {
        if ($this->options) {
            return $this->options;
        }

        $configData = $this->carrierConfig->getCode($this->code);

        foreach ($configData as $code => $title) {
            $this->options[] = [
                'value' => $code,
                'label' => __($title),
            ];
        }

        return $this->options;
    }
}
