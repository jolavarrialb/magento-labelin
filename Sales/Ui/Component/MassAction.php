<?php

declare(strict_types=1);

namespace Labelin\Sales\Ui\Component;

use Labelin\Sales\Helper\Designer as DesignerHelper;
use Magento\Backend\Model\Auth\Session;
use Magento\Framework\View\Element\UiComponent\ContextInterface;

class MassAction extends \Magento\Ui\Component\MassAction
{
    protected const ACTIONS = ['assign_designer', 'unassign_designer'];

    /** @var DesignerHelper */
    protected $designerHelper;

    public function __construct(
        ContextInterface $context,
        DesignerHelper $designerHelper,
        array $components = [],
        array $data = []
    ) {
        $this->designerHelper = $designerHelper;

        parent::__construct($context, $components, $data);
    }

    public function prepare(): void
    {
        parent::prepare();

        if (!$this->designerHelper->getCurrentAuthUserRole()) {
            return;
        }

        if ($this->designerHelper->getCurrentAuthUserRole()->getId() === $this->designerHelper->getDesignerRole()->getId()) {

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
}
