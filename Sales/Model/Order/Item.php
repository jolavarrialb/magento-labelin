<?php

declare(strict_types=1);

namespace Labelin\Sales\Model\Order;

use Labelin\Sales\Exception\MaxArtworkDeclineAttemptsReached;
use Labelin\Sales\Helper\Config\ArtworkDecline as ArtworkDeclineHelper;
use Labelin\Sales\Model\Order;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product\Type;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Framework\Api\AttributeValueFactory;
use Magento\Framework\Api\ExtensionAttributesFactory;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Exception\LocalizedException;
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

    /**
     * @return $this
     * @throws MaxArtworkDeclineAttemptsReached
     */
    public function incrementArtworkDeclinesCount(): self
    {
        if (!$this->isArtworkDeclineAvailable()) {
            throw new MaxArtworkDeclineAttemptsReached(__('Max artwork declines reached.'));
        }

        $qty = 0;
        if (!$this->artworkDeclineHelper->hasArtworkUnlimitedDeclines()) {
            $qty = $this->getArtworkDeclinesCount() + 1;
        }

        $this->setData('artwork_declines_count', $qty);

        $this->_eventManager->dispatch('labelin_order_item_decline_after', ['order_item' => $this]);

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

    /**
     * @return $this
     * @throws LocalizedException
     */
    public function approveArtwork(): self
    {
        if (!$this->isArtworkApproveAvailable()) {
            throw new LocalizedException(__('Approve denied. Please contact your designer.'));
        }

        $this->setData('is_artwork_approved', true);

        $this->_eventManager->dispatch('labelin_order_item_approve_after', ['order_item' => $this]);

        return $this;
    }

    public function isArtworkApproved(): bool
    {
        return (bool)$this->getData('is_artwork_approved');
    }

    public function isArtworkApproveAvailable(): bool
    {
        if (!$this->getOrder()) {
            return false;
        }

        if ($this->getProductType() === Type::TYPE_SIMPLE) {
            return true;
        }

        return $this->getProductType() === Configurable::TYPE_CODE &&
            $this->getOrder()->getStatus() === Order::STATUS_REVIEW;
    }
}
