<?php

declare(strict_types=1);

namespace Labelin\DesignerReport\Block\Adminhtml\Sales\DesignerReport;

use Labelin\DesignerReport\Model\ResourceModel\Report\DesignerReport\Collection;
use Magento\Reports\Block\Adminhtml\Grid\AbstractGrid;
use Magento\Reports\Block\Adminhtml\Sales\Grid\Column\Renderer\Date;

class Grid extends AbstractGrid
{
    /** @var string */
    protected $_columnGroupBy = 'period';

    protected function _construct()
    {
        parent::_construct();
        $this->setCountTotals(true);
    }

    public function getResourceCollectionName(): string
    {
        return Collection::class;
    }

    protected function _prepareColumns(): Grid
    {
        $this->addColumn(
            'period',
            [
                'header' => __('Interval'),
                'index' => 'period',
                'sortable' => false,
                'period_type' => $this->getPeriodType(),
                'renderer' => Date::class,
                'totals_label' => __('Total'),
                'html_decorators' => ['nobr'],
                'header_css_class' => 'col-period',
                'column_css_class' => 'col-period',
            ]
        );

        $this->addColumn(
            'designer_name',
            [
                'header' => __('Designer'),
                'index' => 'designer_name',
                'type' => 'string',
                'sortable' => false,
                'header_css_class' => 'col-product',
                'column_css_class' => 'col-product',
            ]
        );

        if ($this->getFilterData()->getStoreIds()) {
            $this->setStoreIds(explode(',', $this->getFilterData()->getStoreIds()));
        }

        $this->addColumn(
            'production_ticket_qty',
            [
                'header' => __('Production Ticket Qty'),
                'index' => 'production_ticket_qty',
                'type' => 'number',
                'total' => 'sum',
                'sortable' => false,
                'header_css_class' => 'col-qty',
                'column_css_class' => 'col-qty',
            ]
        );

        $this->addExportType('*/*/exportDesignerReportCsv', __('CSV'));
        $this->addExportType('*/*/exportDesignerReportExcel', __('Excel XML'));

        return parent::_prepareColumns();
    }
}
