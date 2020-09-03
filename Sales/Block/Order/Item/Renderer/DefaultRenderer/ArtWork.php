<?php

declare(strict_types=1);

namespace Labelin\Sales\Block\Order\Item\Renderer\DefaultRenderer;

use Labelin\Sales\Helper\Artwork as ArtworkHelper;
use Magento\Framework\View\Element\Template;
use Magento\Sales\Model\Order\Item;

class ArtWork extends \Magento\Framework\View\Element\Template
{
    /** @var ArtworkHelper */
    protected $artworkHelper;

    /**
     * @var Item | null
     */
    protected $item;

    public function __construct(Template\Context $context, array $data = [], ArtworkHelper $artworkHelper)
    {

        $this->artworkHelper = $artworkHelper;
        $this->item = $this->getItem() ?? null;
        parent::__construct($context, $data);
    }

    protected function getComponentOptions()
    {
        return $this->artworkHelper->getArtworkComponentOptions();
//        $this->setData('width', 200);
//        $this->setData('height', 200);
    }

}
