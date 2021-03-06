<?php

declare(strict_types=1);

namespace Labelin\Sales\Model;

use Labelin\Sales\Helper\Config\OrderAccess as OrderAccessHelper;
use Labelin\Sales\Helper\Designer as DesignerHelper;
use Labelin\Sales\Model\Order\Item;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Directory\Model\CurrencyFactory;
use Magento\Eav\Model\Config as EavConfig;
use Magento\Framework\Api\AttributeValueFactory;
use Magento\Framework\Api\ExtensionAttributesFactory;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Locale\ResolverInterface;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Sales\Api\InvoiceManagementInterface;
use Magento\Sales\Api\OrderItemRepositoryInterface;
use Magento\Sales\Model\Order as MagentoOrder;
use Magento\Sales\Model\Order\Config;
use Magento\Sales\Model\Order\ProductOption;
use Magento\Sales\Model\Order\Status\HistoryFactory;
use Magento\Sales\Model\ResourceModel\Order\Address\CollectionFactory as AddressCollectionFactory;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory as OrderCollectionFactory;
use Magento\Sales\Model\ResourceModel\Order\Creditmemo\CollectionFactory as CreditmemoCollectionFactory;
use Magento\Sales\Model\ResourceModel\Order\Invoice\CollectionFactory as InvoiceCollectionFactory;
use Magento\Sales\Model\ResourceModel\Order\Item\CollectionFactory;
use Magento\Sales\Model\ResourceModel\Order\Payment\CollectionFactory as PaymentCollectionFactory;
use Magento\Sales\Model\ResourceModel\Order\Shipment\CollectionFactory as ShipmentCollectionFactory;
use Magento\Sales\Model\ResourceModel\Order\Shipment\Track\CollectionFactory as TrackingCollectionFactory;
use Magento\Sales\Model\ResourceModel\Order\Status\History\CollectionFactory as HistoryCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\User\Model\User;

class Order extends MagentoOrder
{
    public const STATUS_REVIEW = 'designer_review';
    public const STATUS_IN_PRODUCTION = 'in_production';
    public const STATUS_OVERDUE = 'overdue';
    public const STATUS_PENDING = 'pending';
    public const STATUS_READY_TO_SHIP = 'ready_to_ship';

    /** @var array */
    protected $overdueAvailableStatuses;

    /** @var DesignerHelper */
    protected $designerHelper;

    /** @var OrderAccessHelper */
    protected $orderAccessHelper;

    public function __construct(
        Context $context,
        Registry $registry,
        ExtensionAttributesFactory $extensionFactory,
        AttributeValueFactory $customAttributeFactory,
        TimezoneInterface $timezone,
        StoreManagerInterface $storeManager,
        Config $orderConfig,
        ProductRepositoryInterface $productRepository,
        CollectionFactory $orderItemCollectionFactory,
        Visibility $productVisibility,
        InvoiceManagementInterface $invoiceManagement,
        CurrencyFactory $currencyFactory,
        EavConfig $eavConfig,
        HistoryFactory $orderHistoryFactory,
        AddressCollectionFactory $addressCollectionFactory,
        PaymentCollectionFactory $paymentCollectionFactory,
        HistoryCollectionFactory $historyCollectionFactory,
        InvoiceCollectionFactory $invoiceCollectionFactory,
        ShipmentCollectionFactory $shipmentCollectionFactory,
        CreditmemoCollectionFactory $memoCollectionFactory,
        TrackingCollectionFactory $trackCollectionFactory,
        OrderCollectionFactory $salesOrderCollectionFactory,
        PriceCurrencyInterface $priceCurrency,
        ProductCollectionFactory $productListFactory,
        OrderAccessHelper $orderAccessHelper,
        DesignerHelper $designerHelper,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = [],
        ResolverInterface $localeResolver = null,
        ProductOption $productOption = null,
        OrderItemRepositoryInterface $itemRepository = null,
        SearchCriteriaBuilder $searchCriteriaBuilder = null,
        ScopeConfigInterface $scopeConfig = null,
        array $overdueAvailableStatuses = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $extensionFactory,
            $customAttributeFactory,
            $timezone,
            $storeManager,
            $orderConfig,
            $productRepository,
            $orderItemCollectionFactory,
            $productVisibility,
            $invoiceManagement,
            $currencyFactory,
            $eavConfig,
            $orderHistoryFactory,
            $addressCollectionFactory,
            $paymentCollectionFactory,
            $historyCollectionFactory,
            $invoiceCollectionFactory,
            $shipmentCollectionFactory,
            $memoCollectionFactory,
            $trackCollectionFactory,
            $salesOrderCollectionFactory,
            $priceCurrency,
            $productListFactory,
            $resource,
            $resourceCollection,
            $data,
            $localeResolver,
            $productOption,
            $itemRepository,
            $searchCriteriaBuilder,
            $scopeConfig
        );

        $this->overdueAvailableStatuses = $overdueAvailableStatuses;
        $this->designerHelper = $designerHelper;
        $this->orderAccessHelper = $orderAccessHelper;
    }

    public function canReview(): bool
    {
        if (!in_array($this->getState(), [static::STATE_PROCESSING, static::STATE_NEW], false)) {
            return false;
        }

        if (!$this->getData('assigned_designer_id')) {
            return false;
        }

        return !in_array(
            $this->getStatus(),
            [
                static::STATUS_REVIEW,
                static::STATUS_IN_PRODUCTION,
                static::STATUS_READY_TO_SHIP,
            ],
            false
        );
    }

    public function canOverdue(): bool
    {
        if (!in_array($this->getState(), [static::STATE_PROCESSING, static::STATE_NEW], false)) {
            return false;
        }

        return in_array($this->getStatus(), $this->getOverdueAvailableStatuses(), false);
    }

    public function canReorder(): bool
    {
        return $this->_canReorder(true) && $this->getState() === static::STATE_COMPLETE;
    }

    public function canReorderIgnoreSalable(): bool
    {
        return $this->_canReorder(true) && $this->getState() === static::STATE_COMPLETE;
    }

    public function canAddToFavourites(): bool
    {
        return true;
    }

    public function canInvoice(): bool
    {
        if ($this->isPaymentReview()) {
            return false;
        }

        $state = $this->getState();

        if ($state === self::STATE_COMPLETE || $state === self::STATE_CLOSED || $this->isCanceled()) {
            return false;
        }

        if ($this->getActionFlag(self::ACTION_FLAG_INVOICE) === false) {
            return false;
        }

        foreach ($this->getAllItems() as $item) {
            if ($item->getQtyToInvoice() > 0 && !$item->getLockedDoInvoice()) {
                return true;
            }
        }

        return false;
    }

    public function getOverdueAvailableStatuses(): array
    {
        return $this->overdueAvailableStatuses;
    }

    /**
     * @return $this
     * @throws LocalizedException
     */
    public function markAsReview(): self
    {
        if (!$this->canReview()) {
            throw new LocalizedException(__('A review action is not available.'));
        }

        $this
            ->setStatus(static::STATUS_REVIEW)
            ->addStatusToHistory(static::STATUS_REVIEW, __('Order putted on review'));

        $this->_eventManager->dispatch('labelin_order_review_status_after', ['order' => $this]);

        return $this;
    }

    /**
     * @return $this
     * @throws LocalizedException
     */
    public function markAsOverdue(): self
    {
        if (!$this->canOverdue()) {
            throw new LocalizedException(__('An overdue action is not available.'));
        }

        $this
            ->setStatus(static::STATUS_OVERDUE)
            ->addStatusToHistory(static::STATUS_OVERDUE, __('Order putted on overdue'));

        $this->_eventManager->dispatch('labelin_order_overdue_status_after', ['order' => $this]);

        return $this;
    }

    public function markAsReadyToShip(): self
    {
        $this
            ->setStatus(static::STATUS_READY_TO_SHIP)
            ->addStatusToHistory(static::STATUS_READY_TO_SHIP, __('Order is ready to ship.'));

        $this->_eventManager->dispatch('labelin_order_ready_to_ship_status_after', ['order' => $this]);

        return $this;
    }

    public function getDesigner(): ?User
    {
        if (!$this->getData('assigned_designer_id')) {
            return null;
        }

        return $this->designerHelper->getDesignerById((int)$this->getData('assigned_designer_id'));
    }

    public function setItemsArtworkStatus(string $status): void
    {
        foreach ($this->getAllItems() as $item) {
            if (Configurable::TYPE_CODE !== $item->getProductType()) {
                continue;
            }

            $this->_eventManager->dispatch('labelin_sales_order_item_artwork_update_status', [
                'item' => $item,
                'status' => $status,
            ]);
        }
    }

    public function isFavourite(): bool
    {
        return (bool)$this->getData('is_favourite');
    }

    /**
     * @return $this
     * @throws LocalizedException
     */
    public function markAsFavourite(): self
    {
        if (!$this->canAddToFavourites()) {
            throw new LocalizedException(__('Order can\'t be added to favourites.'));
        }

        $this->setData('is_favourite', 1);

        return $this;
    }

    public function removeFromFavourites(): self
    {
        $this->setData('is_favourite', 0);

        return $this;
    }

    public function getAccessor(): OrderAccessHelper
    {
        return $this->orderAccessHelper;
    }

    public function isAvailableCustomerReview(): bool
    {
        return ($this->getState() === static::STATE_NEW ||
                $this->getState() === static::STATE_PROCESSING) &&
            $this->getStatus() === static::STATUS_REVIEW;
    }
}
