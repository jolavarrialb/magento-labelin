<?php

declare(strict_types=1);

namespace Labelin\Sales\Ui\Component\Filters\FilterSelect\Options;

use Labelin\Sales\Helper\Designer as DesignerHelper;

class AssignedDesigner implements \Magento\Framework\Data\OptionSourceInterface
{
    /** @var DesignerHelper */
    protected $designerHelper;

    public function __construct(DesignerHelper $designerHelper)
    {
        $this->designerHelper = $designerHelper;
    }

    public function toOptionArray()
    {
        $designerCollection = $this->designerHelper->getDesignersCollection();
        $designerCollection
            ->addFieldToSelect('user_id')
            ->addFieldToSelect('firstname')
            ->addFieldToSelect('lastname');

        $options = [];
        foreach ($designerCollection as $item) {
            $options[] = [
                'value' => $item->getUserId(),
                'label' => \sprintf('%1$s %2$s', $item->getFirstname(), $item->getLastname())
            ];
        }

        return $options;
    }
}
