<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesShipping\Model\Source;

use Labelin\PitneyBowesOfficialApi\Model\Configuration;
use Magento\Framework\Data\OptionSourceInterface;

class Mode implements OptionSourceInterface
{
    /** @var Configuration */
    protected $config;

    /** @var array */
    protected $options = [];

    public function __construct(Configuration $config)
    {
        $this->config = $config;
    }

    public function toOptionArray(): array
    {
        if ($this->options) {
            return $this->options;
        }

        $configData = $this->config->getHostSettings();

        foreach ($configData as $key => $data) {
            $this->options[] = [
                'value' => $data['url'],
                'label' => $data['label'],
            ];
        }

        return $this->options;
    }
}
