<?php

declare(strict_types=1);

namespace Labelin\Sales\Ui\Component;

use Labelin\Sales\Helper\Designer as DesignerHelper;
use Labelin\Sales\Helper\Shipper as ShipperHelper;
use Magento\Framework\View\Element\UiComponent\ContextInterface;

class MassAction extends \Magento\Ui\Component\MassAction
{
    protected const DISALLOW_ACTIONS = [
        'assign_designer',
        'unassign_designer',
        'cancel',
        'hold_order',
        'unhold_order',
        'pdfinvoices_order',
        'pdfshipments_order',
        'pdfcreditmemos_order',
        'pdfdocs_order',
    ];

    protected const DESIGNER_DISALLOW_ACTIONS = [
        'print_shipping_label',
    ];

    /** @var DesignerHelper */
    protected $designerHelper;

    /** @var ShipperHelper */
    protected $shipperHelper;

    /** @var array */
    protected $config;

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

        $this->config = $this->getConfiguration();

        $this->filterActions(static::DISALLOW_ACTIONS);

        if ($this->designerHelper->isCurrentAuthUserDesigner()) {
            $this->filterActions(static::DESIGNER_DISALLOW_ACTIONS);
        }

        $this->setData('config', $this->config);
    }

    /**
     * @param array $disallowActions
     */
    protected function filterActions($disallowActions = []): void
    {
        $allowedActions = [];

        foreach ($this->config['actions'] as $action) {
            if (!in_array($action['type'], $disallowActions, false)) {
                $allowedActions[] = $action;
            }
        }

        $this->config['actions'] = $allowedActions;
    }
}
