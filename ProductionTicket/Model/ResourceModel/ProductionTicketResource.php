<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Model\ResourceModel;

use Labelin\ProductionTicket\Api\Data\ProductionTicketResourceInterface as ResourceInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class ProductionTicketResource extends AbstractDb implements ResourceInterface
{
    protected const RESOURCE_MAIN_TABLE = 'labelin_production_ticket';

    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'labelin_production_ticket_resource';

    /**
     * Event object
     *
     * @var string
     */
    protected $_eventObject = 'resource';

    /**
     * Model Initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(self::RESOURCE_MAIN_TABLE, 'entity_id');
    }
}
