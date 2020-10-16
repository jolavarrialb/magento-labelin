<?php

declare(strict_types=1);

namespace Labelin\ContactUs\Plugin;

use Magento\Framework\App\Action\Action;

class HomepageContactFormExecute
{
    public function beforeExecute(Action $subject): void
    {
        $paramName = $subject->getRequest()->getParam('name');

        if (is_array($paramName)) {
            $paramName = implode(' ', $paramName);
            $subject->getRequest()->setParam('name', $paramName);
        }
    }
}
