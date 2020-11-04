<?php

declare(strict_types=1);

namespace Labelin\DesignerReport\Block\Adminhtml\Sales;

use Magento\Backend\Block\Widget\Grid\Container;

class DesignerReport extends Container
{
    protected $_template = 'Magento_Reports::report/grid/container.phtml';

    protected function _construct()
    {
        $this->_blockGroup = 'Labelin_DesignerReport';
        $this->_controller = 'adminhtml_sales_designerReport';
        $this->_headerText = __('Designer Report');

        parent::_construct();

        $this->buttonList->remove('add');
        $this->addButton(
            'filter_form_submit',
            ['label' => __('Show Report'), 'onclick' => 'filterFormSubmit()', 'class' => 'primary']
        );
    }

    public function getFilterUrl(): string
    {
        $this->getRequest()->setParam('filter', null);

        return $this->getUrl('*/*/designerReport', ['_current' => true]);
    }
}
