<?php

declare(strict_types=1);

namespace Labelin\Sales\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;
use Magento\Sales\Model\ResourceModel\Order\Status\Collection;
use Magento\Sales\Model\ResourceModel\Order\Status\CollectionFactory;

class SetOrderVisibilityOnFrontendByStatus implements DataPatchInterface, PatchRevertableInterface
{
    private const DEFAULT_STATUS_STATE_VISIBILITY_ON_FRONT = [
        ['status' => 'canceled', 'state' => 'canceled', 'visible_on_front' => 1],
        ['status' => 'closed', 'state' => 'closed', 'visible_on_front' => 1],
        ['status' => 'complete', 'state' => 'complete', 'visible_on_front' => 1],
        ['status' => 'designer_review', 'state' => 'new', 'visible_on_front' => 1],
        ['status' => 'designer_review', 'state' => 'processing', 'visible_on_front' => 1],
        ['status' => 'fraud', 'state' => 'payment_review', 'visible_on_front' => 1],
        ['status' => 'fraud', 'state' => 'processing', 'visible_on_front' => 1],
        ['status' => 'holded', 'state' => 'holded', 'visible_on_front' => 1],
        ['status' => 'in_production', 'state' => 'new', 'visible_on_front' => 1],
        ['status' => 'in_production', 'state' => 'processing', 'visible_on_front' => 1],
        ['status' => 'overdue', 'state' => 'new', 'visible_on_front' => 1],
        ['status' => 'overdue', 'state' => 'processing', 'visible_on_front' => 1],
        ['status' => 'payment_review', 'state' => 'payment_review', 'visible_on_front' => 1],
        ['status' => 'pending', 'state' => 'new', 'visible_on_front' => 1],
        ['status' => 'pending_payment', 'state' => 'pending_payment', 'visible_on_front' => 0],
        ['status' => 'processing', 'state' => 'processing', 'visible_on_front' => 1],
        ['status' => 'ready_to_ship', 'state' => 'new', 'visible_on_front' => 1],
        ['status' => 'ready_to_ship', 'state' => 'processing', 'visible_on_front' => 0],
    ];

    private const STATUS_STATE_VISIBILITY_ON_FRONT = [
        ['status' => 'complete', 'state' => 'complete'],
    ];

    /** @var ModuleDataSetupInterface */
    private $dataSetup;

    /** @var string */
    protected $orderStatusStateTable;

    /** @var CollectionFactory */
    protected $collectionStatusFactory;

    public function __construct(
        ModuleDataSetupInterface $dataSetup,
        CollectionFactory        $collectionStatusFactory
    ) {
        $this->dataSetup = $dataSetup;
        $this->orderStatusStateTable = $dataSetup->getTable('sales_order_status_state');
        $this->collectionStatusFactory = $collectionStatusFactory;
    }

    public function apply(): void
    {
        /** @var Collection $collection */
        $collection = $this->collectionStatusFactory->create();
        $collection->joinStates();

        $this->dataSetup->getConnection()->startSetup();

        foreach ($collection->getItems() as $item) {
            if (!$item->getState() && !$item->getIsDefault() && !$item->getVisibleOnFront()) {
                continue;
            }

            $this->dataSetup->getConnection()->update(
                $this->orderStatusStateTable,
                ['visible_on_front' => 0],
                ['state = ?' => $item->getState(),]
            );

        }

        foreach (static::STATUS_STATE_VISIBILITY_ON_FRONT as $item) {
            $this->dataSetup->getConnection()->update(
                $this->orderStatusStateTable,
                ['visible_on_front' => 1],
                ['state = ?' => $item['state'], 'status = ?' => $item['status']]
            );
        }

        $this->dataSetup->getConnection()->endSetup();
    }

    public function revert(): void
    {
        $this->dataSetup->getConnection()->startSetup();

        foreach (static::DEFAULT_STATUS_STATE_VISIBILITY_ON_FRONT as $item) {
            $this->dataSetup->getConnection()->update(
                $this->orderStatusStateTable,
                ['visible_on_front' => $item['visible_on_front']],
                ['state = ?' => $item->getState(), 'status = ?' => $item->getStatus()]
            );
        }

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
