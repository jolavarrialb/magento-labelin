<?php

declare(strict_types=1);

namespace Labelin\Sales\Ui\Component;

use Labelin\Sales\Helper\Designer as DesignerHelper;
use Labelin\Sales\Helper\Shipper as ShipperHelper;
use Magento\Framework\View\Element\UiComponent\ContextInterface;

class MassAction extends \Magento\Ui\Component\MassAction
{
    protected const ACTIONS = ['assign_designer', 'unassign_designer'];

    /** @var DesignerHelper */
    protected $designerHelper;

    /** @var ShipperHelper */
    protected $shipperHelper;

    public function __construct(
        ContextInterface $context,
        DesignerHelper $designerHelper,
        ShipperHelper $shipperHelper,
        array $components = [],
        array $data = []
    ) {
        $this->designerHelper = $designerHelper;
        $this->shipperHelper = $shipperHelper;

        parent::__construct($context, $components, $data);
    }

    public function prepare(): void
    {
        parent::prepare();

        if (!$this->designerHelper->isCurrentAuthUserDesigner() && !$this->shipperHelper->isCurrentAuthUserShipper()) {
            return;
        }

        $config = $this->getConfiguration();
        $allowedActions = [];

        foreach ($config['actions'] as $action) {
            if (!in_array($action['type'], static::ACTIONS, false)) {
                $allowedActions[] = $action;
            }
        }

        $config['actions'] = $allowedActions;
        $this->setData('config', $config);
    }
}
