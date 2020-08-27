<?php

declare(strict_types=1);

namespace Labelin\Sales\Plugin\Block\Adminhtml\Order\View;

use Labelin\Sales\Helper\Designer as DesignerHelper;
use Magento\Backend\Block\Widget\Tab\TabInterface;

class DesignerTabsHandler
{
    /** @var DesignerHelper */
    protected $designerHelper;

    public function __construct(DesignerHelper $designerHelper)
    {
        $this->designerHelper = $designerHelper;
    }

    public function afterCanShowTab(TabInterface $subject, bool $result): bool
    {
        if ($this->designerHelper->isCurrentAuthUserDesigner()) {
            return false;
        }

        return $result;
    }
}
