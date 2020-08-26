<?php

declare(strict_types=1);

namespace Labelin\Sales\Ui\Component;

use Labelin\Sales\Helper\Designer as Helper;
use Magento\Backend\Model\Auth\Session;
use Magento\Framework\View\Element\UiComponent\ContextInterface;

class MassAction extends \Magento\Ui\Component\MassAction
{
    protected const ACTIONS = ['assign_designer', 'unassign_designer'];

    /** @var Helper */
    protected $helper;

    /*** @var Session */
    protected $authSession;

    public function __construct(
        ContextInterface $context,
        Helper $helper,
        Session $authSession,
        array $components = [],
        array $data = []
    ) {
        $this->helper = $helper;
        $this->authSession = $authSession;

        parent::__construct($context, $components, $data);
    }

    public function prepare(): void
    {
        parent::prepare();

        if (!$this->authSession->getUser()) {
            return;
        }

        if ($this->authSession->getUser()->getRole()->getId() === $this->helper->getDesignerRole()->getId()) {

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
