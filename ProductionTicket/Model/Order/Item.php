<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Model\Order;

use Labelin\Sales\Helper\Config\ArtworkDecline as ArtworkDeclineHelper;
use Labelin\Sales\Model\Order\Item as SalesOrderItem;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\Eav\Model\ResourceModel\Entity\Attribute;
use Magento\Framework\Api\AttributeValueFactory;
use Magento\Framework\Api\ExtensionAttributesFactory;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Sales\Model\OrderFactory;
use Magento\Store\Model\StoreManagerInterface;

class Item extends SalesOrderItem
{
    public const ATTRIBUTE_CODE_STICKER_SHAPE = 'sticker_shape';
    public const ATTRIBUTE_CODE_STICKER_TYPE = 'sticker_type';
    public const ATTRIBUTE_CODE_STICKER_SIZE = 'sticker_size';

    public const ARTWORK_TO_PRODUCTION_COLUMN = 'artwork_to_production';

    /** @var Attribute */
    protected $eavAttribute;

    /** @var Json|mixed */
    protected $serializer;

    public function __construct(
        Context $context,
        Registry $registry,
        ExtensionAttributesFactory $extensionFactory,
        AttributeValueFactory $customAttributeFactory,
        OrderFactory $orderFactory,
        StoreManagerInterface $storeManager,
        ProductRepositoryInterface $productRepository,
        ArtworkDeclineHelper $artworkDeclineHelper,
        Attribute $eavAttribute,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        Json $serializer = null,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $extensionFactory,
            $customAttributeFactory,
            $orderFactory,
            $storeManager,
            $productRepository,
            $artworkDeclineHelper,
            $resource,
            $resourceCollection,
            $serializer,
            $data
        );

        $this->serializer = $serializer ?: \Magento\Framework\App\ObjectManager::getInstance()
            ->get(\Magento\Framework\Serialize\Serializer\Json::class);
        $this->eavAttribute = $eavAttribute;
    }

    public function isInProduction(): bool
    {
        return (bool)$this->getData('is_in_production');
    }

    public function isReadyForProduction(): bool
    {
        return $this->isArtworkApproved() && !$this->isInProduction();
    }

    /**
     * @return $this
     * @throws LocalizedException
     */
    public function markAsInProduction(): self
    {
        if (!$this->isReadyForProduction()) {
            throw new LocalizedException(__('A production action is not available.'));
        }

        $this->setData('is_in_production', true);

        return $this;
    }

    public function getShape(): string
    {
        return $this->getProductOptionAttributeValueByCode(static::ATTRIBUTE_CODE_STICKER_SHAPE);
    }

    public function getType(): string
    {
        return $this->getProductOptionAttributeValueByCode(static::ATTRIBUTE_CODE_STICKER_TYPE);
    }

    public function getSize(): string
    {
        return $this->getProductOptionAttributeValueByCode(static::ATTRIBUTE_CODE_STICKER_SIZE);
    }

    /**
     * @return \DateTime
     * @throws \Exception
     */
    public function getApprovalDate(): \DateTime
    {
        return new \DateTime($this->getData('artwork_approval_date'));
    }

    protected function getProductOptionAttributeValueByCode(string $attributeCode): string
    {
        $attributeId = (int)$this->eavAttribute->getIdByCode(Product::ENTITY, $attributeCode);

        foreach ($this->getProductOptionByCode('attributes_info') as $attributeInfo) {
            if ((int)$attributeInfo['option_id'] === $attributeId) {
                return $attributeInfo['value'];
            }
        }

        return '';
    }

    public function setUploadPdfSerializedData(array $pdfInfo): void
    {
        $this->setData(static::ARTWORK_TO_PRODUCTION_COLUMN, $this->serializer->serialize($pdfInfo));
    }

}
