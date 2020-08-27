<?php

declare(strict_types=1);

namespace Labelin\Sales\Observer\Order;

use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Item;

class OnHoldStatusHandler implements ObserverInterface
{
    protected const FILE_OPTION_TYPE = 'file';

    public function execute(Observer $observer): self
    {
        $isOnHoldStatusAvailable = true;

        /** @var Order $order */
        $order = $observer->getOrder();

        foreach ($order->getAllItems() as $orderItem) {
            if ($orderItem->getProductType() !== Configurable::TYPE_CODE) {
                continue;
            }

            /** @var Item $orderItem */
            $options = $orderItem->getProductOptionByCode('options');

            if (empty($options)) {
                break;
            }

            foreach ($options as $option) {
                if ($option['option_type'] === static::FILE_OPTION_TYPE) {
                    $isOnHoldStatusAvailable = false;
                }
            }
        }

        if ($isOnHoldStatusAvailable) {
            $order
                ->setState(Order::STATE_HOLDED)
                ->setStatus(Order::STATE_HOLDED);
        }

        return $this;
    }
}
