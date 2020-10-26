<?php

declare(strict_types=1);

namespace Labelin\Quote\Observer\Order\Item;

use Labelin\Quote\Helper\Reorder;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class ReorderHandler implements ObserverInterface
{
    /** @var Reorder */
    protected $reorderHelper;

    public function __construct(
        Reorder $reorderHelper
    ) {
        $this->reorderHelper = $reorderHelper;
    }

    public function execute(Observer $observer): self
    {
        if (!$this->reorderHelper->isReordered()) {
            return $this;
        }

        $cart = $observer->getCart();

        if (!$cart) {
            return $this;
        }

        $quote = $cart->getQuote();

        foreach ($quote->getAllItems() as $quoteItem) {
            if (Configurable::TYPE_CODE !== $quoteItem->getProductType()) {
                continue;
            }

            $quoteItem->setData('is_reordered', true);
        }

        $quote->save();

        return $this;
    }
}
