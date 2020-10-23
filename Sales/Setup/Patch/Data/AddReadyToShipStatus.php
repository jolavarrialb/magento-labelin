<?php

declare(strict_types=1);

namespace Labelin\Sales\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;

class AddReadyToShipStatus implements DataPatchInterface, PatchRevertableInterface
{
    protected const STATUS = 'ready_to_ship';

    /** @var ModuleDataSetupInterface */
    private $moduleDataSetup;

    /** @var string */
    protected $statusTable;

    /** @var string */
    protected $stateTable;

    public function __construct(ModuleDataSetupInterface $moduleDataSetup)
    {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->statusTable = $moduleDataSetup->getTable('sales_order_status');
        $this->stateTable = $moduleDataSetup->getTable('sales_order_status_state');
    }

    /**
     * @throws \Exception
     */
    public function apply(): void
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        try {
            $this->moduleDataSetup->getConnection()->insertArray(
                $this->statusTable,
                ['status', 'label'],
                [['status' => static::STATUS, 'label' => 'Ready To Ship']]
            );

            $this->moduleDataSetup->getConnection()->insertArray(
                $this->stateTable,
                ['status', 'state', 'is_default', 'visible_on_front'],
                [
                    [static::STATUS, 'processing', 0, 1],
                    [static::STATUS, 'new', 0, 1],
                ]
            );
        } catch (\Exception $exception) {
            $this->moduleDataSetup->getConnection()->rollBack();
            throw $exception;
        }

        $this->moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * @throws \Exception
     */
    public function revert(): void
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        try {
            $this->moduleDataSetup->getConnection()->delete($this->statusTable, 'status = ' . static::STATUS);
            $this->moduleDataSetup->getConnection()->delete($this->stateTable, 'status = ' . static::STATUS);
        } catch (\Exception $exception) {
            $this->moduleDataSetup->getConnection()->rollBack();
            throw $exception;
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
