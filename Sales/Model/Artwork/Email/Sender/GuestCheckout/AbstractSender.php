<?php

declare(strict_types=1);

namespace Labelin\Sales\Model\Artwork\Email\Sender\GuestCheckout;

use Labelin\Sales\Model\Artwork\Email\Sender\AbstractSender as SalesAbstractSender;
use Labelin\Sales\Model\Order\Item;
use Magento\Framework\DataObject;
use Magento\Sales\Model\Order as MagentoOrder;

abstract class AbstractSender extends SalesAbstractSender
{
    abstract public function getTemplateVars(): array;

    public function send(Item $item): void
    {
        $this->setItem($item);

        if (!$this->isSendingAvailable() || !$this->getDesigner() || !$this->getOrder()) {
            return;
        }

        $this->identityContainer->setEmailIdentity([
            'email' => $this->getDesigner()->getEmail(),
            'name' => $this->getDesigner()->getName(),
        ]);

        $transportObject = new DataObject($this->getTemplateVars());
        $this->templateContainer->setTemplateVars($transportObject->getData());

        $this->checkAndSend($this->getOrder());
    }

    protected function prepareTemplate(MagentoOrder $order): void
    {
        parent::prepareTemplate($order);

        $this->identityContainer->setCustomerEmail($order->getCustomerEmail());
        $this->identityContainer->setCustomerName($order->getCustomerName());
    }
}
