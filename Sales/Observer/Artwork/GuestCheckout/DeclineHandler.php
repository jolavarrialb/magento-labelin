<?php

declare(strict_types=1);

namespace Labelin\Sales\Observer\Artwork\GuestCheckout;

use Labelin\Sales\Exception\MaxArtworkDeclineAttemptsReached;
use Labelin\Sales\Helper\Designer as DesignerHelper;
use Labelin\Sales\Model\Order\Item;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Message\ManagerInterface as MessageManager;
use Magento\Sales\Api\OrderRepositoryInterface;

class DeclineHandler implements ObserverInterface
{
    /** @var MessageManager */
    protected $messageManager;

    /** @var OrderRepositoryInterface */
    protected $orderRepository;

    /** @var DesignerHelper */
    protected $designerHelper;

    public function __construct(
        MessageManager $messageManager,
        DesignerHelper $designerHelper,
        OrderRepositoryInterface $orderRepository
    ) {
        $this->messageManager = $messageManager;
        $this->orderRepository = $orderRepository;
        $this->designerHelper = $designerHelper;
    }

    public function execute(Observer $observer): self
    {
        /** @var Item $item */
        $item = $observer->getData('item');
        $comment = $observer->getData('comment');

        try {
            $item->incrementArtworkDeclinesCount();

            $order = $item->getOrder();
            if ($order) {
                $authUser = $this->designerHelper->getCurrentAuthUser();
                $order->addStatusToHistory($order->getStatus(), sprintf(
                    '%s (%s): %s',
                    $authUser ? $authUser->getName() : '',
                    $authUser ? $authUser->getRole()->getRoleName() : '',
                    $comment
                ));

                $this->orderRepository->save($order);
            }

            $this->messageManager->addSuccessMessage(__('Artwork was successfully declined.'));

        } catch (MaxArtworkDeclineAttemptsReached $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());
        }

        return $this;
    }
}
