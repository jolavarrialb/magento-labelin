<?php

declare(strict_types=1);

namespace Labelin\Sales\Plugin\Controller\Adminhtml\Order;

use Labelin\Sales\Helper\Designer as DesignerHelper;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Message\ManagerInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Controller\Adminhtml\Order\View as MageView;

class View
{
    /** @var DesignerHelper */
    protected $designerHelper;

    /** @var OrderRepositoryInterface */
    protected $orderRepository;

    /** @var RedirectFactory */
    protected $resultRedirectFactory;

    /** @var ManagerInterface */
    protected $messageManager;

    public function __construct(
        DesignerHelper $designerHelper,
        OrderRepositoryInterface $orderRepository,
        RedirectFactory $resultRedirectFactory,
        ManagerInterface $messageManager
    ) {
        $this->designerHelper = $designerHelper;
        $this->orderRepository = $orderRepository;
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->messageManager = $messageManager;
    }

    public function aroundExecute(MageView $subject, \Closure $proceed)
    {
        if (!$this->designerHelper->isCurrentAuthUserDesigner()) {
            return $proceed();
        }

        if (!$this->designerHelper->getCurrentAuthUser()) {
            return $proceed();
        }

        $order = $this->orderRepository->get($subject->getRequest()->getParam('order_id'));

        if ($order->getData('assigned_designer_id') !== $this->designerHelper->getCurrentAuthUser()->getId()) {
            $this->messageManager->addErrorMessage(__('You don\'t have permissions to view this order.'));

            return $this->resultRedirectFactory->create()->setPath('sales/order/index');
        }

        return $proceed();
    }
}
