<?php

declare(strict_types=1);

namespace Labelin\Sales\Block\Adminhtml\Order;

use Labelin\Sales\Helper\Acl as AclHelper;
use Labelin\Sales\Helper\Config\OrdersChart as ChartHelper;
use Labelin\Sales\Model\Order;
use Magento\Backend\Block\Template;
use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Model\ResourceModel\Order\Collection as OrderCollection;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory as OrderCollectionFactory;

class Chart extends Template
{
    /** @var OrderCollectionFactory */
    protected $orderCollectionFactory;

    /** @var OrderCollection */
    protected $orderCollection;

    /** @var AclHelper */
    protected $aclHelper;

    /** @var ChartHelper */
    protected $chartHelper;

    /** @var array */
    protected $availableStatusesForChart;

    public function __construct(
        Template\Context $context,
        OrderCollectionFactory $orderCollectionFactory,
        AclHelper $aclHelper,
        ChartHelper $chartHelper,
        array $availableStatusesForChart = [],
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->orderCollectionFactory = $orderCollectionFactory;
        $this->availableStatusesForChart = $availableStatusesForChart;
        $this->orderCollection = $this->getOrderCollection();

        $this->aclHelper = $aclHelper;
        $this->chartHelper = $chartHelper;
    }

    public function isAvailable(): bool
    {
        return $this->aclHelper->isAllowedAclOrdersChart();
    }

    public function getOrderCollection(): OrderCollection
    {
        $orderCollection = $this
            ->initOrderCollection()
            ->addAttributeToFilter('status', ['in' => $this->availableStatusesForChart]);

        $orderCollection
            ->getSelect()
            ->reset(\Zend_Db_Select::COLUMNS)
            ->columns([
                'status' => 'status',
                'qty'    => 'COUNT(status)',
            ])
            ->order('status ASC')
            ->group('status');

        return $orderCollection;
    }

    /**
     * @return string[]
     * @throws LocalizedException
     */
    public function getLabels(): array
    {
        $labels = [];
        foreach ($this->orderCollection as $order) {
            /** @var Order $order */
            $labels[] = sprintf('%s: %u order(s)', $order->getStatusLabel(), $order->getData('qty'));
        }

        return $labels;
    }

    public function getValues(): array
    {
        $values = [];
        foreach ($this->orderCollection as $order) {
            /** @var Order $order */
            $values[$order->getStatus()] = $order->getData('qty');
        }

        return $values;
    }

    public function getWidth(): int
    {
        return $this->chartHelper->getChartWidth();
    }

    public function getHeight(): int
    {
        return $this->chartHelper->getChartHeight();
    }

    protected function initOrderCollection(array $data = []): OrderCollection
    {
        return $this->orderCollectionFactory->create($data);
    }
}
