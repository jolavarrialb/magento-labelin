<?php

declare(strict_types=1);

namespace Labelin\Sales\Controller\Adminhtml\Order;

use Labelin\Sales\Model\Order;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;

class MassDesignerUnAssign extends MassDesignerAbstract
{
    /**
     * @return ResponseInterface|ResultInterface
     */
    public function execute()
    {
        $orderIds = $this->getRequest()->getParam('selected');

        if (empty($orderIds)) {
            $this->messageManager->addErrorMessage(__('Nothing to save. Please select orders rows'));

            return $this->_redirect($this->_redirect->getRefererUrl());
        }

        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('entity_id', $orderIds, 'in')
            ->create();

        $orders = $this->orderRepository->getList($searchCriteria)->getItems();

        foreach ($orders as $order) {
            /** @var $order Order */
            $order->setItemsArtworkStatus($this->artworkHelper::ARTWORK_STATUS_DESIGNER_UN_ASSIGNED);

            $order->setData('assigned_designer_id', null);
            $this->orderRepository->save($order);
        }

        $this->messageManager->addSuccessMessage(__(
            '%1 order(s) was unassigned from the designers.',
            count($orderIds)
        ));

        return $this->_redirect($this->_redirect->getRefererUrl());
    }
}
