<?php


namespace Labelin\DesignerReport\Model\ResourceModel\Report\DesignerReport;

use Labelin\DesignerReport\Model\ResourceModel\Report\DesignerReport;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface;
use Magento\Framework\Data\Collection\EntityFactory;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Select;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Model\ResourceModel\Report;
use Magento\Sales\Model\ResourceModel\Report\Bestsellers\Collection as BestsellersCollection;
use Psr\Log\LoggerInterface;

class Collection extends BestsellersCollection
{
    /** @var array */
    protected $_selectedColumns = [];

    /** @var array */
    protected $tableForPeriod = [
        'daily' => DesignerReport::AGGREGATION_DAILY,
        'monthly' => DesignerReport::AGGREGATION_MONTHLY,
        'yearly' => DesignerReport::AGGREGATION_YEARLY,
    ];

    public function __construct(
        EntityFactory $entityFactory,
        LoggerInterface $logger,
        FetchStrategyInterface $fetchStrategy,
        ManagerInterface $eventManager,
        Report $resource,
        AdapterInterface $connection = null
    ) {
        $resource->init($this->getTableByAggregationPeriod('daily'), 'entity_id');

        parent::__construct(
            $entityFactory,
            $logger,
            $fetchStrategy,
            $eventManager,
            $resource,
            $connection
        );
    }

    protected function getOrderedField(): string
    {
        return 'production_ticket_qty';
    }

    protected function _getSelectedColumns(): array
    {
        $connection = $this->getConnection();

        if ($this->_selectedColumns) {
            return $this->_selectedColumns;
        }

        if ($this->isTotals()) {
            return $this->getAggregatedColumns();
        }

        $this->_selectedColumns = [
            'period' => sprintf('MAX(%s)', $connection->getDateFormatSql('period', '%Y-%m-%d')),
            $this->getOrderedField() => 'SUM(' . $this->getOrderedField() . ')',
            'designer_name' => 'MAX(designer_name)',
        ];

        if (DesignerReport::TYPE_YEAR === $this->_period) {
            $this->_selectedColumns['period'] = $connection->getDateFormatSql('period', '%Y');

            return $this->_selectedColumns;
        }

        if (DesignerReport::TYPE_MONTH === $this->_period) {
            $this->_selectedColumns['period'] = $connection->getDateFormatSql('period', '%Y-%m');

            return $this->_selectedColumns;
        }

        return $this->_selectedColumns;
    }

    /**
     * @param string $from
     * @param string $to
     *
     * @return Select
     * @throws LocalizedException
     */
    protected function _makeBoundarySelect($from, $to): Select
    {
        $connection = $this->getConnection();
        $cols = $this->_getSelectedColumns();
        $cols[$this->getOrderedField()] = 'SUM(' . $this->getOrderedField() . ')';
        $select = $connection
            ->select()
            ->from($this->getResource()->getMainTable(), $cols)
            ->where('period >= ?', $from)
            ->where('period <= ?', $to)
            ->group('designer_name')
            ->order($this->getOrderedField() . ' ' . Select::SQL_DESC);

        $this->_applyStoresFilterToSelect($select);

        return $select;
    }

    protected function _applyAggregatedTable(): self
    {
        $select = $this->getSelect();

        if (!$this->_period) {
            $cols = $this->_getSelectedColumns();
            $cols[$this->getOrderedField()] = 'SUM(' . $this->getOrderedField() . ')';

            if ($this->_from || $this->_to) {
                $mainTable = $this->getTable($this->getTableByAggregationPeriod('daily'));
                $select->from($mainTable, $cols);
            } else {
                $mainTable = $this->getTable($this->getTableByAggregationPeriod('yearly'));
                $select->from($mainTable, $cols);
            }

            $select
                ->where(new \Zend_Db_Expr($mainTable . '.designer_name IS NOT NULL'))
                ->group('designer_name')
                ->order($this->getOrderedField() . ' ' . Select::SQL_DESC);

            return $this;
        }

        if (DesignerReport::TYPE_YEAR === $this->_period) {
            $mainTable = $this->getTable($this->getTableByAggregationPeriod('yearly'));
            $select->from($mainTable, $this->_getSelectedColumns());
        } elseif (DesignerReport::TYPE_MONTH === $this->_period) {
            $mainTable = $this->getTable($this->getTableByAggregationPeriod('monthly'));
            $select->from($mainTable, $this->_getSelectedColumns());
        } else {
            $mainTable = $this->getTable($this->getTableByAggregationPeriod('daily'));
            $select->from($mainTable, $this->_getSelectedColumns());
        }

        if (!$this->isTotals()) {
            $select->group(['period', 'designer_name']);
        }

        return $this;
    }
}
