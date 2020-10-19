<?php

declare(strict_types=1);

namespace Labelin\Sales\Model\Artwork\Email\Sender\GuestCheckout\Welcome;

use Labelin\Sales\Model\Artwork\Email\Sender\AbstractSender;
use Labelin\Sales\Model\Order\Item;
use Magento\Framework\DataObject;
use Magento\Sales\Model\Order as MagentoOrder;

class WithArtworkSender extends AbstractSender
{
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

        $transport = [
            'template_subject' => __(
                'Artwork welcome email. Order #%1. Order Item #%2',
                $this->getOrder()->getIncrementId(),
                $this->getItem()->getId(),
            ),
            'customer_name' => $this->getOrder()->getCustomerName(),
        ];

        $transportObject = new DataObject($transport);
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
