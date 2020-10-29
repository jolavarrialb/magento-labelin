<?php

declare(strict_types=1);

namespace Labelin\Sales\Plugin\Block\Adminhtml\Order\View;

use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\UrlInterface;
use Magento\Sales\Block\Adminhtml\Order\View as MageView;

class ProductionButton
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
        if (!$subject->getOrder()->isAllItemsReadyForProduction() || $subject->getOrder()->isAllItemsInProduction()) {
            return;
        }

        $message = __('Are you sure you want to change status to production?');
        $url = $this->url->getUrl('sales/order_view/productionAction/order_id/' . $subject->getOrderId());

        $subject->addButton(
            'production',
            [
                'label' => __('In Production'),
                'class' => __('In Production'),
                'id' => 'order-view-production-button',
                'onclick' => "confirmSetLocation('{$message}', '{$url}')"
            ]
        );
    }
}
