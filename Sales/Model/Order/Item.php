<?php

declare(strict_types=1);

namespace Labelin\Sales\Model\Order;

use Labelin\Sales\Helper\Config\ArtworkDecline as ArtworkDeclineHelper;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Api\AttributeValueFactory;
use Magento\Framework\Api\ExtensionAttributesFactory;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Sales\Model\Order\Item as MagentoOrderItem;
use Magento\Sales\Model\OrderFactory;
use Magento\Store\Model\StoreManagerInterface;

class Item extends MagentoOrderItem
{
    /** @var ArtworkDeclineHelper */
    protected $artworkDeclineHelper;

    public function __construct(
        Context $context,
        Registry $registry,
        ExtensionAttributesFactory $extensionFactory,
        AttributeValueFactory $customAttributeFactory,
        OrderFactory $orderFactory,
        StoreManagerInterface $storeManager,
        ProductRepositoryInterface $productRepository,
        ArtworkDeclineHelper $artworkDeclineHelper,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        Json $serializer = null,
        array $data = []
    ) {
        $this->artworkDeclineHelper = $artworkDeclineHelper;

        parent::__construct(
            $context,
            $registry,
            $extensionFactory,
            $customAttributeFactory,
            $orderFactory,
            $storeManager,
            $productRepository,
            $resource,
            $resourceCollection,
            $data,
            $serializer
        );
    }

    public function incrementArtworkDeclinesCount(): self
    {
        $qty = 0;
        if (!$this->artworkDeclineHelper->hasArtworkUnlimitedDeclines()) {
            $qty = $this->getArtworkDeclinesCount() + 1;
        }

        $this->setData('artwork_declines_count', $qty);

        return $this;
    }

    public function getArtworkDeclinesCount(): int
    {
        return (int)$this->getData('artwork_declines_count');
    }

    public function isArtworkDeclineAvailable(): bool
    {
        if ($this->artworkDeclineHelper->hasArtworkUnlimitedDeclines()) {
            return true;
        }

        return $this->getArtworkDeclinesCount() < $this->artworkDeclineHelper->getDeclinesQty();
    }
}
