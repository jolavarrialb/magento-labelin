<?php

declare(strict_types=1);

namespace Labelin\Sales\Helper;

use Labelin\Sales\Model\Order as LabelinOrder;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Sales\Api\OrderItemRepositoryInterface;
use Magento\Sales\Model\Order;
use Labelin\Sales\Model\Order\Item;

class Artwork extends AbstractHelper
{
    public const FILE_OPTION_TYPE = 'file';

    public const ARTWORK_STATUS_DECLINE = 'decline';
    public const ARTWORK_STATUS_APPROVE = 'approve';
    public const ARTWORK_STATUS_AWAITING_DESIGNER = 'awaiting_designer_action';
    public const ARTWORK_STATUS_AWAITING_CUSTOMER = 'awaiting_customer_approve';
    public const ARTWORK_STATUS_NO_ACTION = 'no_action';
    public const ARTWORK_STATUS_MAX_CUSTOMER_DECLINE = 'max_decline_count';

    public const FILTER_STATUSES = [
        self::ARTWORK_STATUS_DECLINE,
        self::ARTWORK_STATUS_APPROVE,
        self::ARTWORK_STATUS_AWAITING_CUSTOMER,
        self::ARTWORK_STATUS_AWAITING_DESIGNER,
        self::ARTWORK_STATUS_NO_ACTION,
        self::ARTWORK_STATUS_MAX_CUSTOMER_DECLINE,
    ];

    /** @var OrderItemRepositoryInterface */
    protected $itemRepository;

    public function __construct(
        OrderItemRepositoryInterface $itemRepository,
        Context $context
    ) {
        $this->itemRepository = $itemRepository;
        parent::__construct($context);
    }

    public function isArtworkAttachedToOrder(Order $order): bool
    {
        $isArtworkAttached = true;

        foreach ($order->getAllItems() as $orderItem) {
            /** @var Item $orderItem */

            if ($orderItem->getProductType() !== Configurable::TYPE_CODE) {
                continue;
            }

            $isArtworkAttached = false;

            $options = $orderItem->getProductOptionByCode('options');

            if (empty($options)) {
                break;
            }

            foreach ($options as $option) {
                if ($option['option_type'] === static::FILE_OPTION_TYPE) {
                    $isArtworkAttached = true;
                }
            }
        }

        return $isArtworkAttached;
    }

    public function getOrderArtworkLinks(Order $order): array
    {
        $artworks = [];

        if (!$this->isArtworkAttachedToOrder($order)) {
            return $artworks;
        }

        foreach ($order->getAllItems() as $orderItem) {
            /** @var Item $orderItem */
            $options = $orderItem->getProductOptionByCode('options');

            if (empty($options)) {
                continue;
            }

            foreach ($options as $option) {
                if ($option['option_type'] === static::FILE_OPTION_TYPE) {
                    $artworks[] = [
                        'link' => $option['value'],
                        'status' => $this->getArtworkStatus($orderItem),
                    ];
                }
            }
        }

        return $artworks;
    }

    public function isArtworkAttachedToOrderItem(Item $item): bool
    {
        $isArtworkAttached = false;

        $options = $item->getProductOptionByCode('options');

        if (empty($options)) {
            return $isArtworkAttached;
        }

        foreach ($options as $option) {
            if ($option['option_type'] === static::FILE_OPTION_TYPE) {
                $isArtworkAttached = true;
            }
        }

        return $isArtworkAttached;
    }

    protected function getArtworkStatus(Item $item): string
    {
        return $item->getArtworkStatus();
    }

    public function getArtworkProductOptionByItem(Item $item): array
    {
        $productOptions = $item->getProductOptions();

        if (!is_array($productOptions) || !isset($productOptions['options'])) {

            return [];
        }

        foreach ($productOptions['options'] as $productOption) {
            if (array_key_exists('option_type', $productOption) &&
                $productOption['option_type'] === static::FILE_OPTION_TYPE
            ) {
                return $productOption;
            }
        }

        return [];
    }

    public function isArtworkInReview(Item $item): bool
    {
        return $item->getOrder()->getStatus() === LabelinOrder::STATUS_REVIEW;
    }
}
