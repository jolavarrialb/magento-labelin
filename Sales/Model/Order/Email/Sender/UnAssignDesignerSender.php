<?php

declare(strict_types=1);

namespace Labelin\Sales\Model\Order\Email\Sender;

use Labelin\Sales\Model\Order;
use Magento\Framework\DataObject;
use Magento\User\Model\User;

class UnAssignDesignerSender extends AbstractSender
{
    public function send(Order $order, User $designer): void
    {
        $this
            ->setDesigner($designer)
            ->setOrder($order);

        if (!$this->isSendingAvailable()) {
            return;
        }

        $transport = [
            'order_url' => $this->urlBuilder->getUrl('sales/order/view', ['order_id' => $order->getId()]),
            'designer_name' => $this->getDesigner()->getName(),
        ];

        $transportObject = new DataObject($transport);
        $this->templateContainer->setTemplateVars($transportObject->getData());

        $this->checkAndSend($order);
    }
}
