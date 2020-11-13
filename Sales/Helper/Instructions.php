<?php

declare(strict_types=1);

namespace Labelin\Sales\Helper;

use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Sales\Api\OrderItemRepositoryInterface;
use Magento\Sales\Model\Order;
use Labelin\Sales\Model\Order\Item;

class Instructions extends AbstractHelper
{
    public const TEXT_OPTION_TYPE = 'area';

    /** @var OrderItemRepositoryInterface */
    protected $itemRepository;

    public function __construct(
        OrderItemRepositoryInterface $itemRepository,
        Context $context
    ) {
        $this->itemRepository = $itemRepository;
        parent::__construct($context);
    }

    public function isInstructionsAttachedToOrder(Order $order): bool
    {
        $isInstructionsAttached = true;

        foreach ($order->getAllItems() as $orderItem) {
            /** @var Item $orderItem */

            if ($orderItem->getProductType() !== Configurable::TYPE_CODE) {
                continue;
            }

            $isInstructionsAttached = false;

            $options = $orderItem->getProductOptionByCode('options');

            if (empty($options)) {
                break;
            }

            foreach ($options as $option) {
                if ($option['option_type'] === static::TEXT_OPTION_TYPE) {
                    $isInstructionsAttached = true;
                }
            }
        }

        return $isInstructionsAttached;
    }

    public function isInstructionsAttachedToOrderItem(Item $item): bool
    {
        $isInstructionsAttached = false;

        $options = $item->getProductOptionByCode('options');

        if (empty($options)) {
            return $isInstructionsAttached;
        }

        foreach ($options as $option) {
            if ($option['option_type'] === static::TEXT_OPTION_TYPE) {
                $isInstructionsAttached = true;
            }
        }

        return $isInstructionsAttached;
    }

    public function getInstructionsByItem(Item $item): array
    {
        $productOptions = $item->getProductOptions();

        if (!is_array($productOptions) || !isset($productOptions['options'])) {

            return [];
        }

        foreach ($productOptions['options'] as $productOption) {
            if (array_key_exists('option_type', $productOption) &&
                $productOption['option_type'] === static::TEXT_OPTION_TYPE
            ) {
                return $productOption;
            }
        }

        return [];
    }
}
