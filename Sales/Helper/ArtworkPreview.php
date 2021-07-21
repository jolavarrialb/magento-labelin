<?php

declare(strict_types=1);

namespace Labelin\Sales\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Labelin\Sales\Helper\Config\ArtworkSizes;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\Serialize\Serializer\Json;
use Labelin\Sales\Helper\Artwork as ArtworkHelper;
use Magento\Catalog\Model\Product\Option\UrlBuilder;
use Magento\Sales\Model\Order\Item;
use Labelin\Sales\Model\Order\Item as LabelinItem;

class ArtworkPreview extends AbstractHelper
{
    protected const PDF_APPLICATION_TYPE = 'application/pdf';
    protected const EPS_APPLICATION_TYPE = 'application/postscript';

    /** @var ArtworkSizes */
    protected $artworkSizesHelper;

    /**@var array|mixed|null */
    protected $item = null;

    /** @var Json|mixed|null */
    protected $json;

    /** @var array */
    protected $optionValues = [];

    /** @var ArtworkHelper */
    protected $artworkHelper;

    /** @var UrlBuilder */
    protected $url;

    /** @var FileFactory */
    protected $fileFactory;

    public function __construct(
        ArtworkSizes $artworkSizes,
        UrlBuilder $url,
        ArtworkHelper $artworkHelper,
        Json $json = null
    ) {
        $this->json = $json ?: \Magento\Framework\App\ObjectManager::getInstance()->get(Json::class);
        $this->artworkSizesHelper = $artworkSizes;
        $this->url = $url;
        $this->artworkHelper = $artworkHelper;
    }

    public function initItemOptions(Item $item): bool
    {
        if (null === $item) {
            return false;
        }

        $options = $this->getOrderOptions($item);

        if (!empty($options['option_value'])) {
            $this->optionValues = $this->json->unserialize($options['option_value']);
        }

        return true;
    }

    public function getArtworkType(): string
    {
        return $this->optionValues['type'] ?? "";
    }

    public function getArtworkUrl(): string
    {
        $optionUrl = $this->optionValues['url'];

        return $this->url->getUrl($optionUrl['route'], $optionUrl['params']) ?? '#';
    }

    public function getArtworkLabel(): string
    {
        return $this->optionValues['title'] ?? "";
    }

    public function getArtworkWidth(): int
    {
        return $this->artworkSizesHelper->getConfigHeight();
    }

    public function getArtworkHeight(): int
    {
        return $this->artworkSizesHelper->getConfigWidth();
    }

    protected function getOrderOptions($item = null): array
    {
        $result = [];

        if (null === $item) {
            return $result;
        }

        $options = $item->getProductOptions();

        if (!$options || !isset($options['options'])) {
            return $result;
        }

        foreach ($options['options'] as $key => $option) {
            if (is_array($option) && !empty($option['option_type']) &&
                $option['option_type'] === $this->artworkHelper::FILE_OPTION_TYPE
            ) {
                $result = $option;
            }
        }

        return $result;
    }

    public function getArtworkOptionsPathByItem(LabelinItem $item): string
    {
        $value = $this->getArtworkOptionsValueByItem($item);

        return !empty($value['quote_path']) ? $value['quote_path'] : $value['order_path'];
    }

    public function getArtworkFileNameByItem(LabelinItem $item): string
    {
        $value = $this->getArtworkOptionsValueByItem($item);

        return $value['title'] ?? '';
    }

    public function getArtworkOptionsValueByItem(LabelinItem $item): array
    {
        $options = $this->getOrderOptions($item);

        if (empty($options) || empty($options['option_value'])) {
            return [];
        }

        return $this->json->unserialize($options['option_value']);
    }

    public function isPdf(): bool
    {
        return $this->getArtworkType() === static::PDF_APPLICATION_TYPE;
    }

    public function isEps(): bool
    {
        return $this->getArtworkType() === static::EPS_APPLICATION_TYPE;
    }
}
