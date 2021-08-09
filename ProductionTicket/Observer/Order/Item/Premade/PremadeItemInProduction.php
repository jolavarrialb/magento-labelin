<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Observer\Order\Item\Premade;

use Labelin\ProductionTicket\Observer\Order\Item\AbstractItemInProduction;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Model\Order\Item;

class PremadeItemInProduction extends AbstractItemInProduction implements ObserverInterface
{
    /**
     * @param Observer $observer
     *
     * @return $this
     * @throws \Exception
     */
    public function execute(Observer $observer): self
    {
        $this->order = $observer->getData('order');

        if (!$this->order) {
            return $this;
        }

        /** @var Item $item */
        foreach ($this->order->getItems() as $orderItem) {
            if ($this->premadeHelper->isPremade($orderItem) && !$orderItem->isInProduction()) {
                $artwork = $this->getArtwork($orderItem);

                $this->saveProductionTicket($orderItem, $artwork);
            }
        }

        return $this;
    }

    /**
     * @param $orderItem
     * @param string $artwork
     *
     * @throws \Exception
     */
    protected function saveProductionTicket($orderItem, string $artwork = ''): void
    {
        $this->productionTicket
            ->setOrderItemId((int)$orderItem->getId())
            ->setOrderId((int)$this->order->getId())
            ->setOrderItemLabel($this->getLabel())
            ->setShape('')
            ->setType($orderItem->getProduct()->getName())
            ->setSize('')
            ->setArtwork($artwork)
            ->setApprovalDate(new \DateTime())
            ->setDesigner($this->premadeHelper::PREMADE_DESIGNER)
            ->setMaterial('')
            ->setIsComplete(false)
            ->setItemQty($this->getOrderItemQty($orderItem));

        $this->productionTicketRepository->save($this->productionTicket);
    }

    protected function getArtwork(Item $item): string
    {
        $product = $item->getProduct();
        $url = $product->getProductUrl(false);
        $productName = $product->getName();

        return sprintf('<a href="%s" target="_blank">%s</a>', $url, $productName);
    }

    protected function getOrderItemQty(Item $orderItem): string
    {
        $result = '';
        $customOptions = $orderItem->getProductOption()->getExtensionAttributes()->getCustomOptions();

        foreach ($customOptions as $option) {
            $productOptionValue = $orderItem->getProduct()
                ->getOptionById($option->getOptionId())
                ->getValueById($option->getOptionValue());
            $result .= $productOptionValue->getTitle() . ' ';
        }

        return sprintf('Ordered: %s - in QTY: %s', $result, (int) $orderItem->getQtyOrdered());
    }
}
