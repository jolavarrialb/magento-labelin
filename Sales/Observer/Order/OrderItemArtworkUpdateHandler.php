<?php

declare(strict_types=1);

namespace Labelin\Sales\Observer\Order;

use Labelin\Sales\Helper\Artwork;
use Labelin\Sales\Model\Product\Option\Type\Artwork\ArtworkUploadValidator;
use Magento\Catalog\Api\Data\ProductCustomOptionInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\CustomOptions\CustomOption;
use Magento\Catalog\Model\Product\Option;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\DataObject;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Sales\Api\OrderItemRepositoryInterface;
use Magento\Sales\Model\Order\Item;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product\Option\UrlBuilder;

class OrderItemArtworkUpdateHandler implements ObserverInterface
{
    protected const CUSTOM_OPTION_DOWNLOAD_URL = 'sales/download/downloadCustomOptionFile';

    /** @var Item */
    protected $item;

    protected $product;

    /** @var array */
    protected $artworkData = [];

    /** @var array */
    protected $customOptionUrlParams = [];

    /** @var string */
    protected $sizes = '';

    /** @var OrderItemRepositoryInterface */
    protected $orderItemRepository;

    /** @var ArtworkUploadValidator */
    protected $fileUploadValidator;

    /** @var SerializerInterface|null */
    protected $json;

    /** @var ProductCustomOptionInterface|mixed */
    protected $productOption;

    /** @var ProductRepositoryInterface */
    protected $productRepositoryInterface;

    /** @var UrlBuilder */
    protected $urlBuilder;

    /** @var ManagerInterface */
    protected $eventManager;

    public function __construct(
        ArtworkUploadValidator $fileUploadValidator,
        OrderItemRepositoryInterface $orderItemRepository,
        ProductRepositoryInterface $productRepositoryInterface,
        UrlBuilder $urlBuilder,
        ManagerInterface $eventManager,
        SerializerInterface $json = null
    ) {
        $this->fileUploadValidator = $fileUploadValidator;
        $this->orderItemRepository = $orderItemRepository;
        $this->json = $json ?: ObjectManager::getInstance()->get(Json::class);
        $this->productRepositoryInterface = $productRepositoryInterface;
        $this->eventManager = $eventManager;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * @param Observer $observer
     *
     * @return $this
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function execute(Observer $observer): self
    {
        $this->item = $observer->getData('item');

        if (!$this->item->getProduct()) {
            return $this;
        }

        $this->product = $this->getProductById($this->item->getProduct()->getId());

        //Upload new image
        $this->artworkData = $this->prepareArtworkProcessQueue();
        $this->uploadArtwork();

        //Update order product_options
        $this->customOptionUrlParams = $this->getCustomOptionsParams();
        $this->updateItemProductOption();
        $this->updateItemExtensionAttributesOption();

        $this->eventManager->dispatch('labelin_order_item_artwork_update_after', ['item' => $this->item]);

        return $this;
    }

    /**
     * @param int|string $id
     *
     * @return ProductInterface
     * @throws NoSuchEntityException
     */
    protected function getProductById($id): ProductInterface
    {
        return $this->productRepositoryInterface->getById((int)$id);
    }

    /**
     * @return array
     * @throws LocalizedException
     */
    protected function prepareArtworkProcessQueue(): array
    {
        $data = new DataObject();
        $data->setData('product', $this->item->getProduct());

        return $this->fileUploadValidator->validate($data, $this->getItemProductOptionForFileUpload());
    }

    protected function getItemProductOptionForFileUpload(): Option
    {
        if (!$this->item->getProduct()) {
            return ObjectManager::getInstance()->get(Option::class);
        }

        $options = $this->item->getProduct()->getOptions();

        foreach ($options as $option) {
            if ($option['type'] === Artwork::FILE_OPTION_TYPE) {
                $this->productOption = $option;

                return $option;
            }
        }

        return ObjectManager::getInstance()->get(Option::class);
    }

    /**
     * @throws LocalizedException
     */
    protected function uploadArtwork(): void
    {
        if (!$this->item->getProduct()) {
            return;
        }

        $this->item->getProduct()->getTypeInstance()->processFileQueue();
    }

    protected function updateItemProductOption(): void
    {
        $productOptions = $this->item->getProductOptions();

        if (!array_key_exists('options', $productOptions)) {
            $key = 0;
        } else {
            $key = $this->getOptionKeyForImageUpdate($productOptions['options']);
        }

        $url['url'] = $this->getDownloadOptionValue();

        $result = [
            'label' => $this->productOption->getTitle(),
            'value' => $this->getValue(),
            'print_value' => $this->getPrintLabel(),
            'option_id' => $this->productOption->getOptionId(),
            'option_type' => $this->productOption->getType(),
            'option_value' => $this->json->serialize(array_merge($this->artworkData, $url)),
            'custom_view' => true,
        ];

        $productOptions['options'][$key] = $result;

        $this->item->setProductOptions($productOptions);
    }

    protected function updateItemExtensionAttributesOption(): void
    {
        $productOption = $this->item->getProductOption();
        $extAttr = $productOption->getExtensionAttributes();

        if (!$extAttr) {
            return;
        }

        $customOptions = $extAttr->getCustomOptions();

        $customOptionIsSavedOnProduct = true;

        foreach ($customOptions as $key => $optionItem) {
            if ((int)$optionItem->getOptionId() === (int)$this->productOption->getOptionId()) {
                $optionItem->setData('option_value', $this->artworkData);
                $customOptionIsSavedOnProduct = false;
            }
        }

        if ($customOptionIsSavedOnProduct) {
            $productCustomOption = ObjectManager::getInstance()->get(CustomOption::class);
            $productCustomOption->setData('option_id', $this->productOption->getOptionId());
            $productCustomOption->setData('option_value', $this->artworkData);
            $customOptions[] = $productCustomOption;
        }

        $extAttr->setCustomOptions($customOptions);
        $productOption->setExtensionAttributes($extAttr);
        $this->item->setProductOption($productOption);
    }

    protected function getValue(): string
    {
        return sprintf(
            "<a href=\"%s\" target=\"_blank\">%s</a> %s",
            $this->urlBuilder->getUrl(static::CUSTOM_OPTION_DOWNLOAD_URL, $this->getCustomOptionsParams()),
            $this->artworkData['title'],
            $this->getUploadedImageSizes()
        );
    }

    protected function getPrintLabel(): string
    {
        return $this->artworkData['title'] . $this->getUploadedImageSizes();
    }

    public function getDownloadOptionValue(): array
    {
        return [
            'route' => static::CUSTOM_OPTION_DOWNLOAD_URL,
            'params' => $this->getCustomOptionsParams(),
        ];
    }

    protected function getCustomOptionsParams(): array
    {
        if (!empty($this->customOptionUrlParams)) {
            return $this->customOptionUrlParams;
        }

        $this->customOptionUrlParams = [
            'id' => $this->item->getId(),
            'key' => $this->artworkData['secret_key'],
        ];

        return $this->customOptionUrlParams;
    }

    protected function getUploadedImageSizes(): string
    {
        if ('' !== $this->sizes) {
            return $this->sizes;
        }

        $this->sizes = sprintf('%d x %d px', $this->artworkData['width'], $this->artworkData['height']);

        return $this->sizes;
    }

    protected function getOptionKeyForImageUpdate($options): int
    {
        foreach ($options as $key => $value) {
            if ($value['option_type'] === Artwork::FILE_OPTION_TYPE) {
                return $key;
            }
        }

        ksort($options);

        return array_key_last($options) + 1;
    }
}
