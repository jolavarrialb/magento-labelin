<?php

declare(strict_types=1);

namespace Labelin\Sales\Observer\Order;

use Labelin\Sales\Helper\Artwork;
use Labelin\Sales\Model\Product\Option\Type\Artwork\ArtworkUploadValidator;
use Magento\Catalog\Model\Product\Option;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\DataObject;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Sales\Api\OrderItemRepositoryInterface;
use Magento\Sales\Model\Order\Item;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Quote\Model\ResourceModel\Quote\Item\Option\CollectionFactory as ItemOptionCollectionFactory;
use Magento\Catalog\Model\Product\Option\UrlBuilder;

class OrderItemArtworkUpdateHandler implements ObserverInterface
{

    public const CUSTOM_OPTION_DOWNLOAD_URL = 'sales/download/downloadCustomOptionFile';

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

    /** @var \Magento\Catalog\Api\Data\ProductCustomOptionInterface|mixed */
    protected $productOption;

    /** @var ProductRepositoryInterface */
    protected $productRepositoryInterface;

    /** @var ItemOptionCollectionFactory */
    protected $itemOptionCollectionFactory;

    /** @var UrlBuilder */
    protected $urlBuilder;

    public function __construct(
        ArtworkUploadValidator $fileUploadValidator,
        OrderItemRepositoryInterface $orderItemRepository,
        ProductRepositoryInterface $productRepositoryInterface,
        ItemOptionCollectionFactory $itemOptionCollectionFactory,
        UrlBuilder $urlBuilder,
        SerializerInterface $json = null
    )
    {
        $this->fileUploadValidator = $fileUploadValidator;
        $this->orderItemRepository = $orderItemRepository;
        $this->json = $json ?: ObjectManager::getInstance()->get(Json::class);
        $this->productRepositoryInterface = $productRepositoryInterface;
        $this->itemOptionCollectionFactory = $itemOptionCollectionFactory;
        $this->urlBuilder = $urlBuilder;
    }

    public function execute(Observer $observer)
    {
        $this->item = $this->orderItemRepository->get($observer->getData('itemId'));
        $this->product = $this->getLoadProduct($this->item->getProduct()->getId());

        //Upload new image
        $this->artworkData = $this->prepareArtworkProcessQueue();
        $this->uploadArtwork();

        //Update order product_options
        $this->customOptionUrlParams = $this->getCustomOptionsParams();
        $this->updateItemData();
    }

    protected function getLoadProduct($id)
    {
        return $this->productRepositoryInterface->getById($id);
    }

    protected function prepareArtworkProcessQueue(): array
    {
        $data = new DataObject();
        $data->setData('product', $this->item->getProduct());

        return $this->fileUploadValidator->validate($data, $this->getItemProductOptionForFileUpload());
    }

    protected function getItemProductOptionForFileUpload(): Option
    {
        $options = $this->item->getProduct()->getOptions();
        foreach ($options as $option) {
            if ($option['type'] === Artwork::FILE_OPTION_TYPE) {
                $this->productOption = $option;

                return $option;
            }
        }
    }

    protected function uploadArtwork(): void
    {
        $this->item->getProduct()->getTypeInstance()->processFileQueue();
    }

    protected function updateItemData(): void
    {
        $tmp = $this->item->getProductOptions();

        $key = $this->getOptionKeyForImageUpdate($tmp['options']);
        $url['url'] = $this->getDownloadOptionValue();

        $result = [
            'label' => $this->productOption->getTitle(),
            'value' => $this->getValue(),
            'print_value' => $this->getPrintLabel(),
            'option_id' => $this->productOption->getOptionId(),
            'option_type' => $this->productOption->getType(),
            'option_value' => $this->json->serialize(array_merge($this->artworkData, $url)),
            'custom_view' => true
        ];

        $tmp['options'][$key] = $result;

        $this->item->setProductOptions($tmp);
        $this->orderItemRepository->save($this->item);
    }

    protected function getValue(): string
    {
        return sprintf(
            '<a href="%s" target="_blank">%s</a> %s',
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
        return $result = [
            'route' => static::CUSTOM_OPTION_DOWNLOAD_URL,
            'params' => $this->getCustomOptionsParams()
        ];

    }

    protected function getCustomOptionsParams(): array
    {
        if (!empty($this->customOptionUrlParams)) {
            return $this->customOptionUrlParams;
        }
        $this->customOptionUrlParams = [
            'id' => $this->item->getId(),
            'key' => $this->artworkData['secret_key']
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
    }
}
