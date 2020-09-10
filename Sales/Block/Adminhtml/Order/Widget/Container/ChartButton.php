<?php

declare(strict_types=1);

namespace Labelin\Sales\Block\Adminhtml\Order\Widget\Container;

use Labelin\Sales\Helper\Acl as AclHelper;
use Magento\Backend\Block\Widget\Container;
use Magento\Backend\Block\Widget\Context;

class ChartButton extends Container
{
    /** @var AclHelper */
    protected $aclHelper;

    public function __construct(
        Context $context,
        AclHelper $aclHelper,
        array $data = []
    ) {
        $this->aclHelper = $aclHelper;

        parent::__construct($context, $data);
    }

    protected function _prepareLayout(): self
    {
        if (!$this->aclHelper->isAllowedAclOrdersChart()) {
            return parent::_prepareLayout();
        }

        $addButtonProps = [
            'id'      => 'order-pie-chart-link',
            'label'   => __('Show Order Chart'),
            'class'   => 'primary',
            'onclick' => '#',
        ];
        $this->buttonList->add('add_new', $addButtonProps);

        return parent::_prepareLayout();
    }
}
