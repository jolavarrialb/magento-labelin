<?php

declare(strict_types=1);

namespace Labelin\Sales\Controller\Adminhtml\Order;

use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;

class MassDesignerAssign extends MassDesignerAbstract
{
    /**
     * @return ResponseInterface|ResultInterface
     */
    public function execute()
    {
        $designerId = $this->getRequest()->getParam('designerId');
        $orderIds = $this->getRequest()->getParam('selected');

        if (!$designerId || empty($orderIds)) {
            $this->messageManager->addErrorMessage(__('Nothing to save. Please select orders rows'));

            return $this->_redirect($this->_redirect->getRefererUrl());
        }

        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('entity_id', $orderIds, 'in')
            ->create();

        $orders = $this->orderRepository->getList($searchCriteria)->getItems();

        foreach ($orders as $order) {
            $order->setData('assigned_designer_id', $designerId);
            $this->orderRepository->save($order);
        }

        $this->messageManager->addSuccessMessage(__('%1 order(s) was assigned to the designer.', count($orderIds)));

        return $this->_redirect($this->_redirect->getRefererUrl());
    }
}
