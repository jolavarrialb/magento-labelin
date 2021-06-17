<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Model\Order\Pdf\Renderers;

use Labelin\ProductionTicket\Helper\ProductionTicketImage;
use Labelin\Sales\Helper\Product\Premade;
use Labelin\Sales\Model\Order\Item as OrderItem;
use Magento\Framework\Filesystem;
use Magento\Framework\Filter\FilterManager;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Magento\Tax\Helper\Data;

class ItemPremade extends ItemArtwork
{
    /** @var Premade */
    protected $premadeHelper;

    public function __construct(
        Premade $premadeHelper,
        ProductionTicketImage $productionTicketHelper,
        Context $context,
        Registry $registry,
        Data $taxData,
        Filesystem $filesystem,
        FilterManager $filterManager,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct(
            $productionTicketHelper,
            $context,
            $registry,
            $taxData,
            $filesystem,
            $filterManager,
            $resource,
            $resourceCollection,
            $data
        );

        $this->premadeHelper = $premadeHelper;
    }

    /**
     * @param OrderItem $item
     * @return string
     */
    protected function getImagePath(OrderItem $item): string
    {
        return $this->premadeHelper->getProductAbsolutePath($item->getProduct());
    }
}
