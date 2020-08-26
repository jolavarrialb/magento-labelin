<?php

declare(strict_types=1);

namespace Labelin\Sales\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;

class AddCustomSalesStatuses implements DataPatchInterface, PatchRevertableInterface
{
    /** @var ModuleDataSetupInterface */
    private $moduleDataSetup;

    /** @var string */
    protected $statusTable;

    /** @var string[] */
    protected $tableColumns = ['status', 'label'];

    public function __construct(ModuleDataSetupInterface $moduleDataSetup)
    {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->statusTable = $moduleDataSetup->getTable('sales_order_status');
    }

    public function apply(): void
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        $updateData = [];
        $newStatuses = [
            'designer_review' => __('Review'),
            'in_production' => __('In Production'),
            'overdue' => __('Overdue'),
        ];

        foreach ($newStatuses as $status => $label) {
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

    public function revert(): void
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        $statusesForDelete = [
            'designer_review',
            'in_production',
            'overdue',
        ];

        try {
            foreach ($statusesForDelete as $status) {
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

    public static function getDependencies(): array
    {
        return [];
    }

    public function getAliases(): array
    {
        return [];
    }
}
