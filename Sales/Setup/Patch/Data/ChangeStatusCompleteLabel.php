<?php

declare(strict_types=1);

namespace Labelin\Sales\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;

class ChangeStatusCompleteLabel implements DataPatchInterface, PatchRevertableInterface
{
    private const COMPLETE_STATUS = 'complete';

    private const DEFAULT_COMLPETE_LABEL = 'Complete';

    private const COMPLETED_LABEL = 'Completed';

    /** @var ModuleDataSetupInterface */
    private $dataSetup;

    /** @var string */
    protected $orderStatusStateTable;

    public function __construct(
        ModuleDataSetupInterface $dataSetup
    ) {
        $this->dataSetup = $dataSetup;
        $this->orderStatusStateTable = $dataSetup->getTable('sales_order_status');
    }

    public function apply(): void
    {
        $this->dataSetup->getConnection()->startSetup();

        $this->dataSetup->getConnection()->update(
            $this->orderStatusStateTable,
            ['label' => static::COMPLETED_LABEL],
            ['status = ?' => static::COMPLETE_STATUS]
        );

        $this->dataSetup->getConnection()->endSetup();
    }

    public function revert(): void
    {
        $this->dataSetup->getConnection()->startSetup();

        $this->dataSetup->getConnection()->update(
            $this->orderStatusStateTable,
            ['label' => static::DEFAULT_COMLPETE_LABEL],
            ['status = ?' => static::COMPLETE_STATUS]
        );

        $this->dataSetup->getConnection()->endSetup();
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
