<?php

declare(strict_types=1);

namespace Labelin\Sales\Plugin\Block\Adminhtml\Order;

use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\UrlInterface;
use Magento\Sales\Block\Adminhtml\Order\View as MageView;

class View
{
    /** @var ObjectManagerInterface */
    protected $objectManager;

    /** @var UrlInterface */
    protected $url;

    public function __construct(ObjectManagerInterface $objectManager, UrlInterface $url)
    {
        $this->objectManager = $objectManager;
        $this->url = $url;
    }

    public function beforeSetLayout(MageView $subject): void
    {
        if (!$subject->getOrder()->canReview()) {
            return;
        }

        $message = __('Are you sure you want to review an order?');
        $url = $this->url->getUrl('sales/order_view/reviewAction/order_id/' . $subject->getOrderId());

        $subject->addButton(
            'review',
            [
                'label' => __('Review'),
                'class' => __('review'),
                'id' => 'order-view-review-button',
                'onclick' => "confirmSetLocation('{$message}', '{$url}')"
            ]
        );
    }
}
