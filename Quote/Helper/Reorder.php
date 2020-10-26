<?php

declare(strict_types=1);

namespace Labelin\Quote\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\Session\SessionManagerInterface;

class Reorder extends AbstractHelper
{
    /** @var SessionManagerInterface */
    protected $sessionManager;

    public function __construct(
        SessionManagerInterface $sessionManager
    ) {
        $this->sessionManager = $sessionManager;
    }

    public function isReordered(): bool
    {
        return (bool)$this->sessionManager->getItemsIsReordered();
    }
}
