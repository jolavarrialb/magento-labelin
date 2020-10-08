<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Controller\Adminhtml\Grid;

class Complete extends AbstractComplete
{
    public function getStatus(): bool
    {
        return true;
    }
}
