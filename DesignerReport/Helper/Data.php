<?php

declare(strict_types=1);

namespace Labelin\DesignerReport\Helper;

use Labelin\ProductionTicket\Model\ResourceModel\ProductionTicket\Collection;
use Labelin\ProductionTicket\Model\ResourceModel\ProductionTicket\CollectionFactory;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\DB\Select;
use Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult;

class Data extends AbstractHelper
{
    /** @var CollectionFactory */
    protected $productionTicketCollectionFactory;

    public function __construct(Context $context, CollectionFactory $productionTicketCollectionFactory)
    {
        parent::__construct($context);

        $this->productionTicketCollectionFactory = $productionTicketCollectionFactory;
    }

    /**
     * @return Collection|SearchResult
     */
    public function getDesignerReportDataCollection()
    {
        $collection = $this->initProductionTicketCollection();

        $collection->addFieldToFilter('designer', ['notnull' => true]);

        $collection
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

        return $collection;
    }

    /**
     * @param array $data
     *
     * @return Collection|SearchResult
     */
    protected function initProductionTicketCollection(array $data = [])
    {
        return $this->productionTicketCollectionFactory->create($data);
    }
}
