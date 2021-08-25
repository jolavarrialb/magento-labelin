<?php

declare(strict_types=1);

namespace Labelin\Sales\Block\Order\Item\Renderer\DefaultRenderer;

use Magento\Framework\View\Element\Template;
use Labelin\Sales\Helper\ArtworkPreview as ArtworkPreviewHelper;

class ArtWork extends Template
{
    protected const PDF_ICON = 'Labelin_Sales::images/artwork/icons/pdf-icon.png';

    protected const EPS_ICON = 'Labelin_Sales::images/artwork/icons/eps-icon.png';

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

    public function getArtworkSrc(): string
    {
        if ($this->artworkPreviewHelper->isPdf()) {
            return $this->getViewFileUrl(static::PDF_ICON);
        }

        if ($this->artworkPreviewHelper->isEps()) {
            return $this->getViewFileUrl(static::EPS_ICON);
        }

        return $this->artworkPreviewHelper->getArtworkUrl();
    }
}
