<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Controller\Adminhtml\Grid;

use Labelin\ProductionTicket\Helper\Acl as AclHelper;
use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    /** @var PageFactory */
    protected $resultPageFactory;

    /** @var AclHelper */
    protected $aclHelper;

    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        AclHelper $aclHelper
    ) {
        parent::__construct($context);

        $this->resultPageFactory = $resultPageFactory;
        $this->aclHelper = $aclHelper;
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Magento_Sales::sales_operation');
        $resultPage->getConfig()->getTitle()->prepend(__('Production Ticket'));

        return $resultPage;
    }

    protected function _isAllowed(): bool
    {
        return parent::_isAllowed() && $this->aclHelper->isAllowedAclProductionTicketGrid();
    }
}
