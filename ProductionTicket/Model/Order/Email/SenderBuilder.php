<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Model\Order\Email;

use Magento\Sales\Model\Order\Email\SenderBuilder as MagentoSenderBuilder;

class SenderBuilder extends MagentoSenderBuilder
{
    public function send(): void
    {
        $templateVars = $this->templateContainer->getTemplateVars();

        if (!array_key_exists('attachments', $templateVars) || !$templateVars['attachments']) {
            parent::send();

            return;
        }

        foreach ($templateVars['attachments'] as $attach) {
            if (!$attach['content']) {
                continue;
            }

            $this->transportBuilder->addAttachment(
                file_get_contents($attach['content']),
                $attach['filename'],
                $attach['type']
            );
        }

        parent::send();
    }
}
