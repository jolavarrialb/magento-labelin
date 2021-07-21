<?php

declare(strict_types=1);

namespace Labelin\Sales\Block\Adminhtml\Order\View\Column\Items\Renderer\DefaultRenderer;

use Magento\Backend\Block\Template;
use Labelin\Sales\Helper\ArtworkPreview as ArtworkPreviewHelper;

class ArtWork extends Template
{
    /** @var ArtworkPreviewHelper */
    protected $artworkPreviewHelper;

    /** @var array */
    protected $iconImagesPaths;

    public function __construct(
        Template\Context $context,
        ArtworkPreviewHelper $artworkPreviewHelper,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->artworkPreviewHelper = $artworkPreviewHelper;
        $this->iconImagesPaths = $data;
    }

    public function getHelper(): ArtworkPreviewHelper
    {
        return $this->artworkPreviewHelper;
    }

    public function getArtworkSrc(): string
    {
        if ($this->artworkPreviewHelper->isPdf()) {
            return $this->getViewFileUrl(array_key_exists('pdf_artwork_icon', $this->iconImagesPaths) ? $this->iconImagesPaths['pdf_artwork_icon'] : '#');
        }

        if ($this->artworkPreviewHelper->isEps()) {
            return $this->getViewFileUrl(array_key_exists('eps_artwork_icon', $this->iconImagesPaths) ? $this->iconImagesPaths['eps_artwork_icon'] : '#');
        }

        return $this->artworkPreviewHelper->getArtworkUrl();
    }
}
