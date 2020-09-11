<?php

declare(strict_types=1);

namespace Labelin\Sales\Model\Artwork\Email\Sender;

use Labelin\Sales\Helper\Designer as DesignerHelper;
use Labelin\Sales\Model\Artwork\Email\Container\Identity;
use Labelin\Sales\Model\Order;
use Labelin\Sales\Model\Order\Item;
use Magento\Backend\Model\Url;
use Magento\Sales\Model\Order as MagentoOrder;
use Magento\Sales\Model\Order\Address\Renderer;
use Magento\Sales\Model\Order\Email\Container\Template;
use Magento\Sales\Model\Order\Email\Sender;
use Magento\Sales\Model\Order\Email\SenderBuilderFactory;
use Magento\User\Model\User;
use Psr\Log\LoggerInterface;

abstract class AbstractSender extends Sender
{
    /** @var DesignerHelper */
    protected $designerHelper;

    /** @var Url */
    protected $urlBuilder;

    /** @var Item */
    protected $item;

    /** @var User */
    protected $designer;

    public function __construct(
        Template $templateContainer,
        Identity $identityContainer,
        SenderBuilderFactory $senderBuilderFactory,
        LoggerInterface $logger,
        Renderer $addressRenderer,
        DesignerHelper $designerHelper,
        Url $urlBuilder
    ) {
        parent::__construct($templateContainer, $identityContainer, $senderBuilderFactory, $logger, $addressRenderer);

        $this->designerHelper = $designerHelper;
        $this->urlBuilder = $urlBuilder;
    }

    protected function isSendingAvailable(): bool
    {
        /** @var Order $order */
        $order = $this->getOrder();

        if (!$order || !$order->getData('assigned_designer_id')) {
            return false;
        }

        $designer = $this->getDesigner();

        if (!$designer) {
            return false;
        }

        return true;
    }

    protected function getDesigner(): ?User
    {
        if ($this->designer) {
            return $this->designer;
        }

        if (!$this->getOrder()) {
            return null;
        }

        return $this->designerHelper->getDesignerById((int)$this->getOrder()->getData('assigned_designer_id'));
    }

    protected function getItem(): Item
    {
        return $this->item;
    }

    protected function setItem(Item $item): void
    {
        $this->item = $item;
    }

    protected function getOrder(): ?MagentoOrder
    {
        return $this->getItem()->getOrder();
    }

    protected function prepareTemplate(MagentoOrder $order): void
    {
        parent::prepareTemplate($order);

        if ($this->getDesigner()) {
            $this->identityContainer->setCustomerEmail($this->getDesigner()->getEmail());
            $this->identityContainer->setCustomerName($this->getDesigner()->getName());
        }
    }

    protected function getOrderUrl(): string
    {
        if (!$this->getOrder()) {
            return '';
        }

        return $this->urlBuilder->getUrl('sales/order/view', ['order_id' => $this->getOrder()->getId()]);
    }

    protected function getCustomerName(): string
    {
        if (!$this->getOrder()) {
            return '';
        }

        return $this->getOrder()->getCustomerName();
    }
}
