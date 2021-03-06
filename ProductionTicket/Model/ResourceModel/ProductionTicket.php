<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Model\ResourceModel;

use Labelin\ProductionTicket\Api\Data\ProductionTicketResourceInterface as ResourceInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class ProductionTicket extends AbstractDb implements ResourceInterface
{
    protected const RESOURCE_MAIN_TABLE = 'labelin_production_ticket';

    protected function _construct()
    {
        $this->_init(self::RESOURCE_MAIN_TABLE, 'entity_id');
    }
}
