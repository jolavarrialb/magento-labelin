<?php

declare(strict_types=1);

namespace Labelin\Sales\Model\Order\Email\Sender;

use Magento\Framework\DataObject;

class AssignDesignerSender extends AbstractSender
{
    public function send(): void
    {
        if (!$this->isSendingAvailable()) {
            return;
        }

        $transport = [
            'order_url' => $this->urlBuilder->getUrl('sales/order/view', ['order_id' => $this->getOrder()->getId()]),
            'designer_name' => $this->getDesigner()->getName(),
        ];

        $transportObject = new DataObject($transport);
        $this->templateContainer->setTemplateVars($transportObject->getData());

        $this->checkAndSend($this->getOrder());
    }
}
