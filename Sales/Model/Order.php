<?php

declare(strict_types=1);

namespace Labelin\Sales\Model;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
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

class Order extends MagentoOrder
{
    public const STATUS_REVIEW        = 'designer_review';
    public const STATUS_IN_PRODUCTION = 'in_production';
    public const STATUS_OVERDUE       = 'overdue';

    /** @var array */
    protected $overdueAvailableStatuses;

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
        $this->overdueAvailableStatuses = $overdueAvailableStatuses;

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
    }


    public function canReview(): bool
    {
        if (!in_array($this->getState(), [static::STATE_PROCESSING, static::STATE_NEW], false)) {
            return false;
        }

        return !in_array($this->getStatus(), [static::STATUS_REVIEW, static::STATUS_IN_PRODUCTION], false);
    }

    public function canOverdue(): bool
    {
        if (!in_array($this->getState(), [static::STATE_PROCESSING, static::STATE_NEW], false)) {
            return false;
        }

        return in_array($this->getStatus(), $this->getOverdueAvailableStatuses(), false);
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

        return $this;
    }
}
