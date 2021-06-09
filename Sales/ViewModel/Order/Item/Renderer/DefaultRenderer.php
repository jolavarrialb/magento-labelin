<?php

declare(strict_types=1);

namespace Labelin\Sales\ViewModel\Order\Item\Renderer;

use Labelin\Sales\Helper\Product\Premade;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Sales\Model\Order\Item;

class DefaultRenderer implements ArgumentInterface
{
    /** @var Premade */
    protected $premadeHelper;

    public function __construct(Premade $premadeHelper)
    {
        $this->premadeHelper = $premadeHelper;
    }

    /**
     * @param Item $item
     * @return bool
     */
    public function isPremadeProduct(Item $item): bool
    {
        if ($item) {
            return $this->premadeHelper->isPremade($item);
        }

        return false;
    }
}
