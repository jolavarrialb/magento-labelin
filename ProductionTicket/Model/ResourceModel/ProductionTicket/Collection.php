<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Model\ResourceModel\ProductionTicket;

use Labelin\ProductionTicket\Model\ProductionTicket;
use Labelin\ProductionTicket\Model\ResourceModel\ProductionTicket as ProductionTicketResource;
use Magento\Framework\DB\Select;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(ProductionTicket::class, ProductionTicketResource::class);
    }

    public function prepareDesignerReport(): self
    {
        $this->addFieldToFilter('designer', ['notnull' => true]);

        $this
            ->getSelect()
            ->reset(Select::COLUMNS)
            ->columns([
                "DATE_FORMAT(created_at, '%Y-%m-%d') AS period",
                'designer as designer_name',
                'COUNT(entity_id) as production_ticket_qty',
            ])
            ->group([
                "DATE_FORMAT(created_at, '%Y-%m-%d')",
                'designer',
            ]);

        return $this;
    }
}
