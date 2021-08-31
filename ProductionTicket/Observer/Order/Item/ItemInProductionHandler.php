<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Observer\Order\Item;

use Labelin\ProductionTicket\Model\ProductionTicket;
use Labelin\ProductionTicket\Model\ProductionTicketRepository;
use Labelin\Sales\Helper\Product\Premade;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Sales\Model\Order\Item;
use Labelin\Sales\Helper\Artwork as ArtworkHelper;
//use Labelin\ProductionTicket\Helper\ProductionTicketArtworkPdfToProgrammer as ArtworkHelper;
use Labelin\Sales\Model\Order;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class ItemInProductionHandler extends AbstractItemInProduction implements ObserverInterface
{
    protected const ITEM_EMPTY_ARTWORK = 'ARTWORK IS EMPTY';

    /** @var ArtworkHelper */
    protected $artworkHelper;

    public function __construct(
        ProductionTicketRepository $productionTicketRepository,
        ProductionTicket $productionTicket,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        Premade $premadeHelper,
        ArtworkHelper $artworkHelper
    ) {
        parent::__construct(
            $productionTicketRepository,
            $productionTicket,
            $searchCriteriaBuilder,
            $premadeHelper
        );

        $this->artworkHelper = $artworkHelper;
    }

    /**
     * @param Observer $observer
     *
     * @return $this
     * @throws \Exception
     */
    public function execute(Observer $observer): self
    {
        /** @var Item $orderItem */
        $orderItem = $observer->getData('item');

        if (!$orderItem || !$orderItem->getProduct()) {
            return $this;
        }

        if ($this->premadeHelper->isPremade($orderItem)) {
            return $this;
        }

        /** @var Order $order */
        $this->order = $orderItem->getOrder();

        if (!$this->order) {
            return $this;
        }

        $artwork = $this->getArtwork($orderItem);

        $this->saveProductionTicket($orderItem, $artwork);

        return $this;
    }

    protected function getArtwork(Item $orderItem): string
    {
        $artwork = $this->artworkHelper->getArtworkProductOptionByItem($orderItem);

        return array_key_exists('value', $artwork) ? $artwork['value'] : static::ITEM_EMPTY_ARTWORK ;
    }
}
