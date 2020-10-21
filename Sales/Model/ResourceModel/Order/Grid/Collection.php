<?php

declare(strict_types=1);

namespace Labelin\Sales\Model\ResourceModel\Order\Grid;

use Labelin\Sales\Helper\Designer as DesignerHelper;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface as FetchStrategy;
use Magento\Framework\Data\Collection\EntityFactoryInterface as EntityFactory;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Magento\Sales\Model\ResourceModel\Order;
use Magento\Sales\Model\ResourceModel\Order\Grid\Collection as GridCollection;
use Psr\Log\LoggerInterface as Logger;

/**
 * Order grid collection
 */
class Collection extends GridCollection
{
    /** @var DesignerHelper */
    protected $designerHelper;

    public function __construct(
        EntityFactory $entityFactory,
        Logger $logger,
        FetchStrategy $fetchStrategy,
        EventManager $eventManager,
        DesignerHelper $designerHelper,
        $mainTable = 'sales_order_grid',
        $resourceModel = Order::class
    ) {
        $this->designerHelper = $designerHelper;

        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $mainTable, $resourceModel);
    }

    protected function _initSelect(): self
    {
        parent::_initSelect();

        $this
            ->getSelect()
            ->joinLeft(
                ['sales_order' => $this->getTable('sales_order')],
                'main_table.entity_id = sales_order.entity_id',
                ['assigned_designer_id']
            );
        $this
            ->getSelect()
            ->joinLeft(
                ['users_table' => $this->getTable('admin_user')],
                'sales_order.assigned_designer_id = users_table.user_id',
                ['assigned_designer_name' => $this->getConnection()->getConcatSql(['firstname', 'lastname'], ' ')]
            );
        $this
            ->getSelect()
            ->joinLeft(
                ['order_item' => 'sales_order_item'],
                'main_table.entity_id = order_item.order_id',
                ['artwork_status' => 'group_concat( distinct artwork_status)']
            )
            ->where('order_item.product_type = "configurable" AND order_item.parent_item_id IS NULL')
            ->group('main_table.entity_id');

        if ($this->designerHelper->isCurrentAuthUserDesigner()) {
            $this->addFieldToFilter('assigned_designer_id', $this->designerHelper->getCurrentAuthUser()->getId());
        }

        return $this;
    }
}
