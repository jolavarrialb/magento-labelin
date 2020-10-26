<?php

declare(strict_types=1);

namespace Labelin\Quote\Observer\Order\Item;

use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Session\SessionManagerInterface;

class ReorderHandler implements ObserverInterface
{
    /** @var SessionManagerInterface */
    protected $sessionManager;

    public function __construct(
        SessionManagerInterface $sessionManager
    ) {
        $this->sessionManager = $sessionManager;
    }

    public function execute(Observer $observer): self
    {
        if (!$this->isReordered()) {
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

    protected function isReordered(): bool
    {
        return (bool)$this->sessionManager->getItemsIsReordered();
    }
}
