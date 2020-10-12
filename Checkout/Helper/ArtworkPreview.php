<?php

declare(strict_types=1);

namespace Labelin\Checkout\Helper;

use Labelin\Sales\Helper\Artwork;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Catalog\Model\Product\Option\UrlBuilder;
use Magento\Quote\Api\Data\CartItemInterface;

class ArtworkPreview extends AbstractHelper
{
    /** @var Json */
    protected $serializer;

    /** @var UrlBuilder */
    protected $url;

    public function __construct(Context $context, Json $serializer, UrlBuilder $url)
    {
        parent::__construct($context);

        $this->serializer = $serializer;
        $this->url = $url;
    }

    public function getArtworkUrlByItemAndOptions(CartItemInterface $item, array $productOptions = []): string
    {
        $url = '';

        if (empty($productOptions) || $item->getProduct()->getTypeId() !== Configurable::TYPE_CODE) {
            return $url;
        }

        foreach ($productOptions as $option) {
            if (!array_key_exists('option_type', $option) || $option['option_type'] !== Artwork::FILE_OPTION_TYPE) {
                continue;
            }

            $option = $item->getOptionByCode('option_' . $option['option_id']);
            $optionValue = $this->serializer->unserialize($option->getValue());

            $url = $this->url->getUrl($optionValue['url']['route'], $optionValue['url']['params']);
        }

        return $url;
    }
}
