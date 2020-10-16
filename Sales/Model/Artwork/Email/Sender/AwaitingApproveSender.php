<?php

declare(strict_types=1);

namespace Labelin\Sales\Model\Artwork\Email\Sender;

use Labelin\Sales\Model\Order\Item;
use Magento\Framework\DataObject;

class AwaitingApproveSender extends AbstractSender
{
    public function send(Item $item): bool
    {
        $isSend = false;

        $this->setItem($item);

        if (!$this->isSendingAvailable()) {
            return $isSend;
        }

        $transport = [
            'item_id' => $this->getItem()->getId(),
            'customer_name' => $this->getCustomerName(),
            'exceeded_days' => $this->identityContainer->getExceededDays(),
            'order_url' => $this->getOrderUrl(),
        ];

        $transportObject = new DataObject($transport);
        $this->templateContainer->setTemplateVars($transportObject->getData());

        if ($this->checkAndSend($this->getOrder())) {
            $isSend = true;
        }

        return $isSend;
    }
}
