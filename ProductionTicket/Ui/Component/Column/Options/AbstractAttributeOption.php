<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Ui\Component\Column\Options;

use Magento\Eav\Model\Config as EavConfig;
use Magento\Eav\Model\Entity\Attribute\AttributeInterface;
use Magento\Framework\Data\OptionSourceInterface;

abstract class AbstractAttributeOption implements OptionSourceInterface
{
    /** @var EavConfig */
    protected $eavConfig;

    /** @var array */
    protected $options;

    public function __construct(EavConfig $eavConfig)
    {
        $this->eavConfig = $eavConfig;
    }

    abstract protected function getAttribute(): ?AttributeInterface;

    public function toOptionArray(): array
    {
        if ($this->options) {
            return $this->options;
        }

        $attribute = $this->getAttribute();

        if (!$attribute) {
            return [];
        }

        foreach ($attribute->getSource()->getAllOptions() as $option) {
            $this->options[] = [
                'value' => $option['label'],
                'label' => $option['label'],
            ];
        }

        return $this->options;
    }
}
