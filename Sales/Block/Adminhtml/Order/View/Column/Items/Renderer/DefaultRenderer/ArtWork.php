<?php

declare(strict_types=1);

namespace Labelin\Sales\Block\Adminhtml\Order\View\Column\Items\Renderer\DefaultRenderer;

use Magento\Backend\Block\Template;
use Labelin\Sales\Helper\ItemArtworkOptions as ItemArtworkOptionsHelper ;

class ArtWork extends Template
{
    /** @var ItemArtworkOptionsHelper */
    protected $ItemArtworkOptionsHelper;

    /**
     * ArtWork constructor.
     * @param Template\Context $context
     * @param ItemArtworkOptionsHelper $ItemArtworkOptionsHelper
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        ItemArtworkOptionsHelper $ItemArtworkOptionsHelper,
        array $data = []
    ) {
        $this->ItemArtworkOptionsHelper = $ItemArtworkOptionsHelper;
        parent::__construct($context, $data);
    }

    public function getHelper(): ItemArtworkOptionsHelper
    {
        return $this->ItemArtworkOptionsHelper;
    }
}
