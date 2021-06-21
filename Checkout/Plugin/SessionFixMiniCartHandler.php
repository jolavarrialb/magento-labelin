<?php

declare(strict_types=1);

namespace Labelin\Checkout\Plugin;

use Magento\Checkout\Model\Session as MagentoSession;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class SessionFixMiniCartHandler
{

    /**
     * @param MagentoSession $subject
     * @param callable $proceed
     * @return mixed
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function aroundClearQuote(MagentoSession $subject, callable $proceed): MagentoSession
    {
        $result = $proceed();
        $result->setLoadInactive(false);
        $result->replaceQuote($subject->getQuote()->save());

        return $result;
    }
}
