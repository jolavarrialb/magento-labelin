<?php

declare(strict_types=1);

namespace Labelin\Sales\Plugin\Controller;

use Magento\Framework\Session\SessionManagerInterface;
use Magento\Sales\Controller\AbstractController\Reorder as ReorderAbstract;

class Reorder
{
    /** @var SessionManagerInterface */
    protected $sessionManager;

    public function __construct(
        SessionManagerInterface $sessionManager

    ) {
        $this->sessionManager = $sessionManager;
    }

    public function aroundExecute(ReorderAbstract $subject, callable $proceed)
    {
        $this->setReorderFlagItems();

        $returnValue = $proceed();

        $this->unsetReorderFlagItems();

        return $returnValue;
    }

    protected function setReorderFlagItems(): void
    {
        $this->sessionManager->setItemsIsReordered(true);
    }

    protected function unsetReorderFlagItems(): void
    {
        $this->sessionManager->setItemsIsReordered(false);
    }
}
