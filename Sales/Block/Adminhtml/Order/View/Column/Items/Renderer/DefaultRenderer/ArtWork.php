<?php

declare(strict_types=1);

namespace Labelin\Sales\Block\Adminhtml\Order\View\Column\Items\Renderer\DefaultRenderer;

use Magento\Backend\Block\Template;
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
