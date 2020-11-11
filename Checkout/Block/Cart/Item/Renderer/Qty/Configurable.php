<?php

declare(strict_types=1);

namespace Labelin\Checkout\Block\Cart\Item\Renderer\Qty;

use Labelin\ConfigurableProduct\Helper\Qty as QtyHelper;
use Magento\Framework\View\Element\Template;

class Configurable extends AbstractRenderer
{
    /** @var QtyHelper */
    protected $qtyHelper;

    public function __construct(
        Template\Context $context,
        QtyHelper $qtyHelper,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->qtyHelper = $qtyHelper;
    }

    public function getAdvancedQty(): array
    {
        $item = current($this->getItem()->getChildren());

        return $this->qtyHelper->setProduct($item->getProduct())->getAdvancedQty();
    }

    public function isAvailableAdvancedQty(): bool
    {
        $item = current($this->getItem()->getChildren());

        return $this->qtyHelper->setProduct($item->getProduct())->isAvailableAdvancedQty();
    }
}
