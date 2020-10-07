<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Controller\Adminhtml\Grid;

class UnComplete extends AbstractComplete
{
    public function getStatus(): bool
    {
        return false;
    }
}
