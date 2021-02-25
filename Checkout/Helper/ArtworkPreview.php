<?php

declare(strict_types=1);

namespace Labelin\Checkout\Helper;

use Labelin\Sales\Helper\Artwork;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Catalog\Model\Product\Option\UrlBuilder;
use Magento\Framework\View\Asset\Repository;
use Magento\Quote\Api\Data\CartItemInterface;

class ArtworkPreview extends AbstractHelper
{
    protected const EPS_IMG_PATH = 'Labelin_ConfigurableProduct::images/source/checkouts/upload-image/eps-icon.png';
    protected const PDF_IMG_PATH = 'Labelin_ConfigurableProduct::images/source/checkouts/upload-image/pdf-icon.png';

    protected const PDF = 'application/pdf';
    protected const EPS = 'application/postscript';

    /** @var Json */
    protected $serializer;

    /** @var UrlBuilder */
    protected $url;

    /** @var Repository */
    protected $assetRepo;

    /** @var RequestInterface */
    protected $request;

    public function __construct(
        Context $context,
        Json $serializer,
        Repository $assetRepo,
        RequestInterface $request,
        UrlBuilder $url
    ) {
        parent::__construct($context);

        $this->assetRepo = $assetRepo;
        $this->request = $request;
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

            $params = ['_secure' => $this->request->isSecure()];

            switch ($optionValue['type']) {
                case static::PDF:
                    $url = $this->assetRepo->getUrlWithParams(static::PDF_IMG_PATH, $params);
                    break;
                case static::EPS:
                    $url = $this->assetRepo->getUrlWithParams(static::EPS_IMG_PATH, $params);
                    break;
                default:
                    $url = $this->url->getUrl($optionValue['url']['route'], $optionValue['url']['params']);
            }
        }

        return $url;
    }
}
