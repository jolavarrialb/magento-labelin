<?php

declare(strict_types=1);

namespace Labelin\Sales\Block\Adminhtml\Order\View\Column\Items\Renderer\DefaultRenderer;

use Labelin\Sales\Helper\Config\ArtworkOptions as ArtworkOptionsHelper;
use Magento\Catalog\Model\Product\OptionFactory;
use Magento\Backend\Block\Template;
use Magento\Framework\Serialize\Serializer\Json;
use Labelin\Sales\Helper\Artwork as ArtworkHelper ;
use Magento\Catalog\Model\Product\Option\UrlBuilder;

class ArtWork extends \Magento\Backend\Block\Template
{
    /** @var ArtworkOptionsHelper */
    protected $artworkOptionsHelper;

    /** @var OptionFactory  */
    protected $productOptionFactory;

    /**@var array|mixed|null */
    protected $item = null;

    /** @var Json|mixed|null */
    protected $serializer;

    /** @var array */
    protected $optionValues = [];

    /** @var ArtworkHelper */
    protected $artworkHelper;

    /** @var UrlBuilder     */
    protected $url;


    /**
     * ArtWork constructor.
     * @param Template\Context $context
     * @param ArtworkOptionsHelper $artworkOptionsHelper
     * @param UrlBuilder $url
     * @param ArtworkHelper $artworkHelper
     * @param Json|null $serializer
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        ArtworkOptionsHelper $artworkOptionsHelper,
        UrlBuilder $url,
        ArtworkHelper $artworkHelper,
        Json $serializer = null,
        array $data = []
    ) {
        $this->serializer = $serializer ?: \Magento\Framework\App\ObjectManager::getInstance()->get(Json::class);
        $this->artworkOptionsHelper = $artworkOptionsHelper;
        $this->url = $url;
        $this->artworkHelper = $artworkHelper;
        parent::__construct($context, $data);

    }

    public function parseItemOptions()
    {
        $options = $this->getOrderOptions($this->getData('item'));

        if (!empty($options['option_value'])) {
            $this->optionValues = $this->serializer->unserialize($options['option_value']);
        }
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
        return $this->artworkOptionsHelper->getConfigHeight();
    }

    public function getArtworkHeight(): int
    {
        return $this->artworkOptionsHelper->getConfigWidth();
    }

    protected function getOrderOptions($item = null): array
    {
        $result = [];
        if (null === $item) {
            return $result;
        }
        $options = $item->getProductOptions();
        if ($options) {
            if (isset($options['options'])) {
                foreach ($options['options'] as $key => $option) {
                    if (is_array($option) && !empty($option['option_type']) && $option['option_type'] === 'file')
                    $result = array_merge($result, $option);
                }
            }
        }

        return $result;
    }
}
