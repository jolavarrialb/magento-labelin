<?php

declare(strict_types=1);

namespace Labelin\Sales\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;

class MapReviewStatusToState implements DataPatchInterface, PatchRevertableInterface
{
    /** @var ModuleDataSetupInterface */
    private $moduleDataSetup;

    /** @var string */
    protected $table;

    public function __construct(ModuleDataSetupInterface $moduleDataSetup)
    {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->table = $moduleDataSetup->getTable('sales_order_status_state');
    }

    /**
     * @throws \Exception
     */
    public function apply(): void
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        try {
            $this->moduleDataSetup->getConnection()->insertArray(
                $this->table,
                ['status', 'state', 'is_default', 'visible_on_front'],
                [
                    ['designer_review', 'processing', 0, 1],
                    ['designer_review', 'new', 0, 1],
                ]
            );
        } catch (\Exception $e) {
            $this->moduleDataSetup->getConnection()->rollBack();
            throw $e;
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
            $this->moduleDataSetup
                ->getConnection()
                ->delete($this->table, 'status = designer_review');
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
