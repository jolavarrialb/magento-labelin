<?php

declare(strict_types=1);

namespace Labelin\Sales\Model\Artwork\Email\Sender\GuestCheckout\Welcome;

use Labelin\Sales\Model\Artwork\Email\Sender\GuestCheckout\AbstractSender;

class WithoutArtworkSender extends AbstractSender
{
    public function getTemplateVars(): array
    {
        if (!$this->getOrder()) {
            return [];
        }

        return [
            'template_subject' => __(
                'Artwork required. Order #%1. Order Item #%2',
                $this->getOrder()->getIncrementId(),
                $this->getItem()->getId(),
            ),
            'customer_name' => $this->getOrder()->getCustomerName(),
        ];
    }
}
