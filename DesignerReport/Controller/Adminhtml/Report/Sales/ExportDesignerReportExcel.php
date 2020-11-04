<?php

declare(strict_types=1);

namespace Labelin\DesignerReport\Controller\Adminhtml\Report\Sales;

use Labelin\DesignerReport\Block\Adminhtml\Sales\DesignerReport\Grid;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Controller\ResultInterface;
use Magento\Reports\Controller\Adminhtml\Report\Sales;

class ExportDesignerReportExcel extends Sales
{
    protected const FILENAME = 'designer_report.xml';

    /**
     * @return ResponseInterface|ResultInterface
     * @throws \Exception
     */
    public function execute()
    {
        $grid = $this->_view->getLayout()->createBlock(Grid::class);
        $this->_initReportAction($grid);

        return $this->_fileFactory->create(
            static::FILENAME,
            $grid->getExcelFile(),
            DirectoryList::VAR_DIR
        );
    }
}
