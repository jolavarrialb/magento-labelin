<?php

declare(strict_types=1);

namespace Labelin\Sales\Model\Order\Email\Sender;

use Labelin\Sales\Helper\Designer as DesignerHelper;
use Labelin\Sales\Model\Order\Email\Container\Identity;
use Labelin\Sales\Model\Order;
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

    /** @var User */
    protected $designer;

    /** @var order */
    protected $order;

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

    protected function getDesigner(): User
    {
        return $this->designer;
    }

    protected function setDesigner(User $designer): self
    {
        $this->designer = $designer;

        return $this;
    }

    protected function setOrder(Order $order): self
    {
        $this->order = $order;

        return $this;
    }

    protected function getOrder(): Order
    {
        return $this->order;
    }

    protected function prepareTemplate(MagentoOrder $order): void
    {
        parent::prepareTemplate($order);

        if ($this->getDesigner()) {
            $this->identityContainer->setCustomerEmail($this->getDesigner()->getEmail());
            $this->identityContainer->setCustomerName($this->getDesigner()->getName());
        }
    }

    protected function isSendingAvailable(): bool
    {
        return $this->getOrder()->getId() && $this->getDesigner()->getId();
    }
}
