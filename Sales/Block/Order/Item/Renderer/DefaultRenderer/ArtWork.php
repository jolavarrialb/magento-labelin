<?php

declare(strict_types=1);

namespace Labelin\Sales\Block\Order\Item\Renderer\DefaultRenderer;

use Magento\Framework\View\Element\Template;
use Labelin\Sales\Helper\ArtworkPreview as ArtworkPreviewHelper ;

class ArtWork extends Template
{
    /** @var ArtworkPreviewHelper */
    protected $artworkPreviewHelper;

    public function __construct(
        Template\Context $context,
        ArtworkPreviewHelper $artworkPreviewHelper,
        array $data = []
    ) {
        $this->artworkPreviewHelper = $artworkPreviewHelper;
        parent::__construct($context, $data);
    }

    public function getHelper(): ArtworkPreviewHelper
    {
        return $this->artworkPreviewHelper;
    }
}
