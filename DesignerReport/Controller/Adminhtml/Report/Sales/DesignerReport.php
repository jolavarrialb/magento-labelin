<?php

declare(strict_types=1);

namespace Labelin\DesignerReport\Controller\Adminhtml\Report\Sales;

use Labelin\DesignerReport\Model\Flag;
use Magento\Reports\Controller\Adminhtml\Report\Sales;

class DesignerReport extends Sales
{
    public function execute(): void
    {
        $this->_showLastExecutionTime(Flag::REPORT_DESIGNER_FLAG_CODE, 'designerreport');

        $this->_initAction()
            ->_setActiveMenu('Labelin_DesignerReport::report_designerreport')
            ->_addBreadcrumb(__('Designer Report'), __('Designer Report'));

        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('Designer Report'));

        $gridBlock = $this->_view->getLayout()->getBlock('adminhtml_sales_designerReport.grid');
        $filterFormBlock = $this->_view->getLayout()->getBlock('grid.filter.form');

        $this->_initReportAction([$gridBlock, $filterFormBlock]);

        $this->_view->renderLayout();
    }
}
