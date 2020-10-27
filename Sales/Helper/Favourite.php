<?php

declare(strict_types=1);

namespace Labelin\Sales\Helper;

use Labelin\Sales\Model\Order;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;

class Favourite extends AbstractHelper
{
    /** @var OrderRepositoryInterface */
    protected $orderRepository;

    public function __construct(Context $context, OrderRepositoryInterface $orderRepository)
    {
        parent::__construct($context);

        $this->orderRepository = $orderRepository;
    }

    /**
     * @param OrderInterface|Order $order
     *
     * @return bool
     */
    public function canAddToFavourites(OrderInterface $order): bool
    {
        return $order->canAddToFavourites() && !$order->isFavourite();
    }

    /**
     * @param OrderInterface|Order $order
     *
     * @return bool
     */
    public function canRemoveToFavourites(OrderInterface $order): bool
    {
        return $order->canAddToFavourites() && $order->isFavourite();
    }

    public function getAddToFavouriteUrl(OrderInterface $order): string
    {
        return $this->_getUrl('sales/order_favourite/add', ['id' => $order->getEntityId()]);
    }

    public function getRemoveFromFavouriteUrl(OrderInterface $order): string
    {
        return $this->_getUrl('sales/order_favourite/remove', ['id' => $order->getEntityId()]);
    }
}
