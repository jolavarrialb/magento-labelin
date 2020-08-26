<?php

namespace Labelin\Sales\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;

class AddCustomSalesStatuses implements DataPatchInterface, PatchRevertableInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @var string
     */
    protected $statusTable;

    /**
     * @var string[]
     */
    protected $tableColumns = ['status', 'label'];


    public function __construct(ModuleDataSetupInterface $moduleDataSetup)
    {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->statusTable = $moduleDataSetup->getTable('sales_order_status');
    }


    /**
     * {@inheritDoc}
     * @throws \Exception
     */
    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        $updateData = [];
        $statuses4Add = [
            'designer_review' => __('Review'),
            'in_production' => __('In Production'),
            'overdue' => __('Overdue'),
        ];

        foreach ($statuses4Add as $status => $label) {
            $updateData[] = ['status' => $status, 'label' => $label];
        }

        try {
            $this->moduleDataSetup->getConnection()->insertArray(
                $this->statusTable,
                $this->tableColumns,
                $updateData
            );
        } catch (\Exception $e) {
            $this->moduleDataSetup->getConnection()->rollBack();
            var_dump($e->getMessage());
            throw $e;
        }

        try {
            $this->moduleDataSetup->getConnection()->update(
                $this->statusTable,
                ['label' => __('Open')],
                ['status = "processing"']
            );
        } catch (\Exception $e) {
            $this->moduleDataSetup->getConnection()->rollBack();
            throw $e;
        }

        $this->moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * {@inheritDoc}
     * @throws \Exception
     */
    public function revert()
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        $statuses4Delete = [
            'designer_review',
            'in_production',
            'overdue',
        ];

        try {
            foreach ($statuses4Delete as $status) {
                $this->moduleDataSetup->getConnection()->delete($this->statusTable, 'status = ' . $status);
            }
        } catch (\Exception $e) {
            $this->moduleDataSetup->getConnection()->rollBack();
            throw $e;
        }

        try {
            $this->moduleDataSetup->getConnection()->update(
                $this->statusTable,
                ['label' => __('Processing')],
                ['status = "processing"']
            );
        } catch (\Exception $e) {
            $this->moduleDataSetup->getConnection()->rollBack();
            throw $e;
        }

        $this->moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * {@inheritDoc}
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * {@inheritDoc}
     */
    public function getAliases()
    {
        return [];
    }
}
