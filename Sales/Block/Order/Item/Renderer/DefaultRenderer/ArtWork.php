<?php

declare(strict_types=1);

namespace Labelin\Sales\Block\Order\Item\Renderer\DefaultRenderer;

use Labelin\Sales\Block\Traits\Order\Items\Renderer\DefaultRenderer\ArtWorkTrait;
use Labelin\Sales\Helper\Config\ArtworkOptions as ArtworkOptionsHelper;
use Magento\Catalog\Model\Product\Option\UrlBuilder;
use Magento\Catalog\Model\Product\OptionFactory;
use Magento\Framework\View\Element\Template;
use Magento\Framework\Serialize\Serializer\Json;
use Labelin\Sales\Helper\Artwork as ArtworkHelper;

class ArtWork extends \Magento\Framework\View\Element\Template
{
    use ArtWorkTrait;

    /** @var ArtworkOptionsHelper */
    protected $artworkOptionsHelper;

    /** @var OptionFactory  */
    protected $productOptionFactory;

    /**@var array|mixed|null */
    protected $item = null;

    /** @var Json|mixed|null */
    private $json;

    /** @var array */
    protected $optionValues = [];

    /** @var UrlBuilder */
    protected $url;

    /** @var ArtworkHelper */
    private $artworkHelper;


    /**
     * ArtWork constructor.
     * @param Template\Context $context
     * @param ArtworkOptionsHelper $artworkOptionsHelper
     * @param UrlBuilder $url
     * @param ArtworkHelper $artworkHelper
     * @param Json|null $json
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        ArtworkOptionsHelper $artworkOptionsHelper,
        UrlBuilder $url,
        ArtworkHelper $artworkHelper,
        Json $json = null,
        array $data = []
    ) {
        $this->json = $json ?: \Magento\Framework\App\ObjectManager::getInstance()->get(Json::class);
        $this->artworkOptionsHelper = $artworkOptionsHelper;
        $this->url = $url;
        $this->artworkHelper = $artworkHelper;
        parent::__construct($context, $data);
    }
}
