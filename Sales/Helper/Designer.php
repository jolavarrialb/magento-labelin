<?php

declare(strict_types=1);

namespace Labelin\Sales\Helper;

use Magento\Authorization\Model\ResourceModel\Role\Collection as RoleCollection;
use Magento\Authorization\Model\Role;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\DataObject;
use Magento\User\Model\ResourceModel\User\Collection as UserCollection;

class Designer extends AbstractHelper
{
    public const DESIGNER_ROLE_NAME = 'Designer';

    /*** @var UserCollection */
    protected $designersCollection;

    /*** @var RoleCollection */
    protected $roleCollection;

    public function __construct(
        Context $context,
        UserCollection $userCollection,
        RoleCollection $roleCollection
    ) {
        parent::__construct($context);

        $this->roleCollection = $roleCollection;
        $this->designersCollection = $userCollection;
    }

    public function getDesignersCollection(): UserCollection
    {
        return $this->designersCollection
            ->addFieldToFilter('is_active', true)
            ->addFieldToFilter('user_role.parent_id', $this->getDesignerRole()->getId());
    }

    /**
     * @return DataObject|Role
     */
    public function getDesignerRole()
    {
        return $this->roleCollection
            ->setRolesFilter()
            ->addFieldToFilter('role_name', static::DESIGNER_ROLE_NAME)
            ->getFirstItem();
    }
}
