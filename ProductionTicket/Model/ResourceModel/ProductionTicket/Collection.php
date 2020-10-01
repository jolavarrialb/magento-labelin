<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Model\ResourceModel\ProductionTicket;

use Labelin\ProductionTicket\Model\ProductionTicket;
use Labelin\ProductionTicket\Model\ResourceModel\ProductionTicketResource;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(ProductionTicket::class, ProductionTicketResource::class);
    }
}
