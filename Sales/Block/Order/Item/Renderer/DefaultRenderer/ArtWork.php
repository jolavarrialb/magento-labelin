<?php

declare(strict_types=1);

namespace Labelin\Sales\Block\Order\Item\Renderer\DefaultRenderer;

use Magento\Framework\View\Element\Template;
use Labelin\Sales\Helper\ArtworkRenderer as ArtworkRendererHelper ;

class ArtWork extends Template
{
    /** @var ArtworkRendererHelper */
    protected $artworkRendererHelper;

    /**
     * ArtWork constructor.
     * @param Template\Context $context
     * @param ArtworkRendererHelper $artworkRendererHelper
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        ArtworkRendererHelper $artworkRendererHelper,
        array $data = []
    ) {
        $this->artworkRendererHelper = $artworkRendererHelper;
        parent::__construct($context, $data);
    }

    public function getHelper(): ArtworkRendererHelper
    {
        return $this->artworkRendererHelper;
    }
}
