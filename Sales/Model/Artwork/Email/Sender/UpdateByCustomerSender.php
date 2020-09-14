<?php

declare(strict_types=1);

namespace Labelin\Sales\Model\Artwork\Email\Sender;

use Labelin\Sales\Model\Order\Item;
use Magento\Framework\DataObject;

class UpdateByCustomerSender extends AbstractSender
{
    public function send(Item $item): void
    {
        $this->setItem($item);

        if (!$this->isSendingAvailable()) {
            return;
        }

        $transport = [
            'item'          => $this->getItem(),
            'customer_name' => $this->getCustomerName(),
            'designer_name' => $this->getDesigner() ? $this->getDesigner()->getName() : '',
            'order_url'     => $this->getOrderUrl(),
        ];

        $transportObject = new DataObject($transport);
        $this->templateContainer->setTemplateVars($transportObject->getData());

        $this->checkAndSend($this->getOrder());
    }
}
