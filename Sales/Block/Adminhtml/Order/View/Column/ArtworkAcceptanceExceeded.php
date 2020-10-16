<?php

declare(strict_types=1);

namespace Labelin\Sales\Block\Adminhtml\Order\View\Column;

use Labelin\Sales\Helper\Config\ArtworkAwaitingCustomerApprove;
use Labelin\Sales\Model\Order\Item;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Framework\View\Element\Template;

class ArtworkAcceptanceExceeded extends Template
{
    /** @var ArtworkAwaitingCustomerApprove */
    protected $awaitingCustomerApprove;

    public function __construct(
        Template\Context $context,
        ArtworkAwaitingCustomerApprove $awaitingCustomerApprove,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->awaitingCustomerApprove = $awaitingCustomerApprove;
    }

    public function isAvailableNotification(): bool
    {
        $orderItem = $this->getOrderItem();

        if (!$orderItem || !$orderItem->getOrder() || $orderItem->getProductType() !== Configurable::TYPE_CODE) {
            return false;
        }

        return $this->getAwaitingCustomerApprove()->isAwaitingCustomerApproveExceeded($orderItem);
    }

    public function getOrderItem(): ?Item
    {
        return $this->getData('item');
    }

    public function getAwaitingCustomerApprove(): ArtworkAwaitingCustomerApprove
    {
        return $this->awaitingCustomerApprove;
    }
}
