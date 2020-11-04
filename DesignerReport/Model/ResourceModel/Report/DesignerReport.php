<?php

declare(strict_types=1);

namespace Labelin\DesignerReport\Model\ResourceModel\Report;

use Labelin\DesignerReport\Helper\Data as DataHelper;
use Labelin\DesignerReport\Model\Flag;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\Stdlib\DateTime\Timezone\Validator;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Reports\Model\FlagFactory;
use Magento\Sales\Model\ResourceModel\Report\AbstractReport;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

class DesignerReport extends AbstractReport
{
    public const TYPE_DAY   = 'day';
    public const TYPE_MONTH = 'month';
    public const TYPE_YEAR  = 'year';

    public const AGGREGATION_DAILY   = 'labelin_designer_report_aggregated_daily';
    public const AGGREGATION_MONTHLY = 'labelin_designer_report_aggregated_monthly';
    public const AGGREGATION_YEARLY  = 'labelin_designer_report_aggregated_yearly';

    protected const AGGREGATION_TABLES = [
        self::AGGREGATION_DAILY,
        self::AGGREGATION_MONTHLY,
        self::AGGREGATION_YEARLY,
    ];

    /** @var ResourceConnection */
    protected $resource;

    /** @var TimezoneInterface */
    protected $timezone;

    /** @var SearchCriteriaBuilder */
    protected $searchCriteriaBuilder;

    /** @var StoreManagerInterface */
    protected $storeManager;

    protected $dataHelper;

    public function __construct(
        Context $context,
        LoggerInterface $logger,
        TimezoneInterface $localeDate,
        FlagFactory $reportsFlagFactory,
        Validator $timezoneValidator,
        DateTime $dateTime,
        ResourceConnection $resource,
        TimezoneInterface $timezone,
        StoreManagerInterface $storeManager,
        DataHelper $dataHelper,
        $connectionName = null
    ) {
        parent::__construct(
            $context,
            $logger,
            $localeDate,
            $reportsFlagFactory,
            $timezoneValidator,
            $dateTime,
            $connectionName
        );

        $this->resource = $resource;
        $this->timezone = $timezone;

        $this->dataHelper = $dataHelper;
        $this->storeManager = $storeManager;
    }

    protected function _construct(): void
    {
        $this->_init(self::AGGREGATION_DAILY, 'entity_id');
    }

    public function aggregate($from = null, $to = null): self
    {
        try {
            $mainTable = $this->getMainTable();
        } catch (LocalizedException $localizedException) {
            $this->_logger->error($localizedException->getMessage());
        }

        $connection = $this->getConnection();

        try {
            $this->truncateTables();

            $insertBatches = [];

            $collection = $this->dataHelper->getDesignerReportDataCollection();

            if ($collection->getSize() === 0) {
                return $this;
            }

            $itemsArray = $collection->toArray();
            $collection = $itemsArray['items'];

            foreach ($collection as $info) {
                $insertBatches[] = [
                    'period' => $info['period'],
                    'store_id' => $this->getDefaultStoreId(),
                    'designer_name' => $info['designer_name'],
                    'production_ticket_qty' => $info['production_ticket_qty'],
                ];
            }

            $tableName = $this->resource->getTableName(self::AGGREGATION_DAILY);

            foreach (array_chunk($insertBatches, 100) as $batch) {
                $connection->insertMultiple($tableName, $batch);
            }

            $this->updateReportMonthlyYearly(
                $connection,
                static::TYPE_MONTH,
                $mainTable,
                self::AGGREGATION_MONTHLY
            );

            $this->updateReportMonthlyYearly(
                $connection,
                static::TYPE_YEAR,
                $mainTable,
                self::AGGREGATION_YEARLY
            );

            $this->_setFlagData(Flag::REPORT_DESIGNER_FLAG_CODE);
        } catch (\Exception $exception) {
            $this->_logger->error($exception->getMessage());
        }

        return $this;
    }

    public function truncateTables(): void
    {
        foreach (static::AGGREGATION_TABLES as $table) {
            $this->_truncateTable($table);
        }
    }

    public function updateReportMonthlyYearly(
        AdapterInterface $connection,
        string $type,
        string $mainTable,
        string $aggregationTableName
    ): self {
        $periodSubSelect = $connection->select();
        $ratingSubSelect = $connection->select();
        $ratingSelect = $connection->select();

        switch ($type) {
            case static::TYPE_YEAR:
                $periodCol = $connection->getDateFormatSql('t.period', '%Y-01-01');
                break;
            case static::TYPE_MONTH:
                $periodCol = $connection->getDateFormatSql('t.period', '%Y-%m-01');
                break;
            default:
                $periodCol = 't.period';
                break;
        }

        $columns = [
            'period' => 't.period',
            'store_id' => 't.store_id',
            'designer_name' => 't.designer_name',
        ];

        if ($type === static::TYPE_DAY) {
            $columns['entity_id'] = 't.entity_id';
        }

        $cols = array_keys($columns);
        $cols['total_qty'] = new \Zend_Db_Expr('SUM(t.production_ticket_qty)');
        $periodSubSelect
            ->from(['t' => $mainTable], $cols)
            ->group(['t.store_id', $periodCol, 't.designer_name'])
            ->order(['t.store_id', $periodCol, 'total_qty DESC']);

        $cols = $columns;
        $cols['production_ticket_qty'] = 't.total_qty';

        $cols['prevStoreId'] = new \Zend_Db_Expr('(@prevStoreId := t.`store_id`)');
        $cols['prevPeriod'] = new \Zend_Db_Expr("(@prevPeriod := {$periodCol})");
        $ratingSubSelect->from($periodSubSelect, $cols);

        $cols = $columns;
        $cols['period'] = $periodCol;
        $cols['production_ticket_qty'] = 't.production_ticket_qty';

        $ratingSelect->from($ratingSubSelect, $cols);

        $sql = $ratingSelect->insertFromSelect($this->getTable($aggregationTableName), array_keys($cols));
        $connection->query("SET @pos = 0, @prevStoreId = -1, @prevPeriod = '0000-00-00'");
        $connection->query($sql);

        return $this;
    }

    protected function getDefaultStoreId(): int
    {
        if (!$this->storeManager->getDefaultStoreView()) {
            return 1;
        }

        return (int)$this->storeManager->getDefaultStoreView()->getId();
    }
}
