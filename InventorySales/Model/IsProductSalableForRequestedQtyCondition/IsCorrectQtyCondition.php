<?php

declare(strict_types=1);

namespace Labelin\InventorySales\Model\IsProductSalableForRequestedQtyCondition;

use Magento\CatalogInventory\Api\StockConfigurationInterface;
use Magento\Framework\Math\Division as MathDivision;
use Magento\InventoryConfigurationApi\Api\GetStockItemConfigurationInterface;
use Magento\InventoryConfigurationApi\Api\Data\StockItemConfigurationInterface;
use Magento\InventoryReservationsApi\Model\GetReservationsQuantityInterface;
use Magento\InventorySalesApi\Model\GetStockItemDataInterface;
use Magento\InventorySalesApi\Api\IsProductSalableForRequestedQtyInterface;
use Magento\InventorySalesApi\Api\Data\ProductSalableResultInterfaceFactory;
use Magento\InventorySalesApi\Api\Data\ProductSalableResultInterface;
use Magento\InventorySalesApi\Api\Data\ProductSalabilityErrorInterfaceFactory;
use Magento\Framework\Phrase;

/**
 * @inheritdoc
 */
class IsCorrectQtyCondition implements IsProductSalableForRequestedQtyInterface
{
    /** @var GetStockItemConfigurationInterface  */
    protected $getStockItemConfiguration;

    /** @var GetReservationsQuantityInterface  */
    protected $getReservationsQuantity;

    /** @var GetStockItemDataInterface  */
    protected $getStockItemData;

    /** @var StockConfigurationInterface  */
    protected $configuration;

    /** @var MathDivision  */
    protected $mathDivision;

    /** @var ProductSalabilityErrorInterfaceFactory  */
    protected $productSalabilityErrorFactory;

    /** @var ProductSalableResultInterfaceFactory  */
    protected $productSalableResultFactory;

    /**
     * IsCorrectQtyCondition constructor.
     * @param GetStockItemConfigurationInterface $getStockItemConfiguration
     * @param StockConfigurationInterface $configuration
     * @param GetReservationsQuantityInterface $getReservationsQuantity
     * @param GetStockItemDataInterface $getStockItemData
     * @param MathDivision $mathDivision
     * @param ProductSalabilityErrorInterfaceFactory $productSalabilityErrorFactory
     * @param ProductSalableResultInterfaceFactory $productSalableResultFactory
     */
    public function __construct(
        GetStockItemConfigurationInterface $getStockItemConfiguration,
        StockConfigurationInterface $configuration,
        GetReservationsQuantityInterface $getReservationsQuantity,
        GetStockItemDataInterface $getStockItemData,
        MathDivision $mathDivision,
        ProductSalabilityErrorInterfaceFactory $productSalabilityErrorFactory,
        ProductSalableResultInterfaceFactory $productSalableResultFactory
    ) {
        $this->getStockItemConfiguration = $getStockItemConfiguration;
        $this->configuration = $configuration;
        $this->getStockItemData = $getStockItemData;
        $this->getReservationsQuantity = $getReservationsQuantity;
        $this->mathDivision = $mathDivision;
        $this->productSalabilityErrorFactory = $productSalabilityErrorFactory;
        $this->productSalableResultFactory = $productSalableResultFactory;
    }

    /**
     * @inheritdoc
     */
    public function execute(string $sku, int $stockId, float $requestedQty): ProductSalableResultInterface
    {
        /** @var StockItemConfigurationInterface $stockItemConfiguration */
        $stockItemConfiguration = $this->getStockItemConfiguration->execute($sku, $stockId);

        if ($this->isMinSaleQuantityCheckFailed($stockItemConfiguration, $requestedQty)) {
            return $this->createErrorResult(
                'is_correct_qty-min_sale_qty',
                __(
                    'The fewest you may purchase is %1',
                    $stockItemConfiguration->getMinSaleQty()
                )
            );
        }

        if ($this->isMaxSaleQuantityCheckFailed($stockItemConfiguration, $requestedQty)) {
            return $this->createErrorResult(
                'is_correct_qty-max_sale_qty',
                __(
                    'The largest you may purchase is %1',
                    $stockItemConfiguration->getMaxSaleQty()
                )
            );
        }

        if ($this->isQuantityIncrementCheckFailed($stockItemConfiguration, $requestedQty)) {
            return $this->createErrorResult(
                'is_correct_qty-qty_increment',
                __(
                    'You can buy this product only in quantities of %1 at a time.',
                    $stockItemConfiguration->getQtyIncrements()
                )
            );
        }

        if ($this->isDecimalQtyCheckFailed($stockItemConfiguration, $requestedQty)) {
            return $this->createErrorResult(
                'is_correct_qty-is_qty_decimal',
                __('You cannot use decimal quantity for this product.')
            );
        }

        return $this->productSalableResultFactory->create(['errors' => []]);
    }

    /**
     * Create Error Result Object
     *
     * @param string $code
     * @param Phrase $message
     * @return ProductSalableResultInterface
     */
    protected function createErrorResult(string $code, Phrase $message): ProductSalableResultInterface
    {
        $errors = [
            $this->productSalabilityErrorFactory->create([
                'code' => $code,
                'message' => $message
            ])
        ];
        return $this->productSalableResultFactory->create(['errors' => $errors]);
    }

    /**
     * Check if decimal quantity is valid
     *
     * @param StockItemConfigurationInterface $stockItemConfiguration
     * @param float $requestedQty
     * @return bool
     */
    protected function isDecimalQtyCheckFailed(
        StockItemConfigurationInterface $stockItemConfiguration,
        float $requestedQty
    ): bool {
        return (!$stockItemConfiguration->isQtyDecimal() && (floor($requestedQty) !== $requestedQty));
    }

    /**
     * Check if min sale condition is satisfied
     *
     * @param StockItemConfigurationInterface $stockItemConfiguration
     * @param float $requestedQty
     * @return bool
     */
    protected function isMinSaleQuantityCheckFailed(
        StockItemConfigurationInterface $stockItemConfiguration,
        float $requestedQty
    ): bool {
        // Minimum Qty Allowed in Shopping Cart
        if ($stockItemConfiguration->getMinSaleQty() && $requestedQty < $stockItemConfiguration->getMinSaleQty()) {
            return true;
        }
        return false;
    }

    /**
     * Check if max sale condition is satisfied
     *
     * @param StockItemConfigurationInterface $stockItemConfiguration
     * @param float $requestedQty
     * @return bool
     */
    protected function isMaxSaleQuantityCheckFailed(
        StockItemConfigurationInterface $stockItemConfiguration,
        float $requestedQty
    ): bool {
        // Maximum Qty Allowed in Shopping Cart
        if ($stockItemConfiguration->getMaxSaleQty() && $requestedQty > $stockItemConfiguration->getMaxSaleQty()) {
            return true;
        }
        return false;
    }

    /**
     * Check if increment quantity condition is satisfied
     *
     * @param StockItemConfigurationInterface $stockItemConfiguration
     * @param float $requestedQty
     * @return bool
     */
    protected function isQuantityIncrementCheckFailed(
        StockItemConfigurationInterface $stockItemConfiguration,
        float $requestedQty
    ): bool {
        // Qty Increments
        $qtyIncrements = $stockItemConfiguration->getQtyIncrements();
        if ($qtyIncrements !== (float)0 && $this->mathDivision->getExactDivision($requestedQty, $qtyIncrements) !== 0) {
            return true;
        }
        return false;
    }
}
