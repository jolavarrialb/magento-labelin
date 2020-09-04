<?php

declare(strict_types=1);

namespace Labelin\Sales\Block\Order\Item\Renderer\DefaultRenderer;

use Labelin\Sales\Helper\Config\ArtworkOptions as ArtworkHelper;
use Magento\Catalog\Model\Product\OptionFactory;
use Magento\Framework\View\Element\Template;
use Magento\Sales\Model\Order\Item;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\UrlInterface;

class ArtWork extends \Magento\Framework\View\Element\Template
{
    /** @var ArtworkHelper */
    protected $artworkHelper;

    /** @var OptionFactory  */
    protected $productOptionFactory;

    /**@var array|mixed|null */
    protected $item = null;

    /** @var array|mixed|null */
    protected $artworkOption;

    /** @var Json|mixed|null */
    private $serializer;

    /** @var array|bool|float|int|mixed|string|null */
    protected $optionValues;

    /** @var UrlInterface */
    protected $url;


    /**
     * ArtWork constructor.
     * @param Template\Context $context
     * @param ArtworkHelper $artworkHelper
     * @param Json|null $serializer
     * @param UrlInterface $url
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        ArtworkHelper $artworkHelper,
        UrlInterface $url,
        Json $serializer = null,
        array $data = []
    ) {
        $this->serializer = $serializer ?: \Magento\Framework\App\ObjectManager::getInstance()->get(Json::class);
        $this->artworkHelper = $artworkHelper;
        $this->url = $url;
        parent::__construct($context, $data);
    }

    public function parseItemOptions()
    {
        $option = $this->getData('option');
        $this->optionValues = $this->serializer->unserialize($option['option_value']);
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
        return $this->optionValues['title'];
    }

    public function getArtworkWidth(): int
    {
        return $this->artworkHelper->getConfigHeight();
    }

    public function getArtworkHeight(): int
    {
        return $this->artworkHelper->getConfigWidth();
    }

}
