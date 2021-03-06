<?php

declare(strict_types=1);

namespace Labelin\Sales\Helper;

use Labelin\Sales\Model\Order;
use Magento\Authorization\Model\ResourceModel\Role\CollectionFactory as RoleCollectionFactory;
use Magento\Authorization\Model\ResourceModel\Role\Collection as RoleCollection;
use Magento\Authorization\Model\Role;
use Magento\Backend\Model\Auth\Session;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\DataObject;
use Magento\User\Model\ResourceModel\User\CollectionFactory as UserCollectionFactory;
use Magento\User\Model\ResourceModel\User\Collection as UserCollection;
use Magento\User\Model\User;

class Designer extends AbstractHelper
{
    public const DESIGNER_ROLE_NAME = 'Designer';

    /*** @var UserCollectionFactory */
    protected $designersCollectionFactory;

    /*** @var RoleCollection */
    protected $roleCollectionFactory;

    /*** @var Session */
    protected $authSession;

    public function __construct(
        Context $context,
        UserCollectionFactory $userCollectionFactory,
        RoleCollectionFactory $roleCollectionFactory,
        Session $authSession
    ) {
        parent::__construct($context);

        $this->roleCollectionFactory = $roleCollectionFactory;
        $this->designersCollectionFactory = $userCollectionFactory;
        $this->authSession = $authSession;
    }

    public function getDesignersCollection(): UserCollection
    {
        return $this->initDesignersCollection()
            ->addFieldToFilter('is_active', true)
            ->addFieldToFilter('user_role.parent_id', $this->getDesignerRole()->getId());
    }

    /**
     * @return DataObject|Role
     */
    public function getDesignerRole()
    {
        return $this->initRoleCollection()
            ->setRolesFilter()
            ->addFieldToFilter('role_name', static::DESIGNER_ROLE_NAME)
            ->getFirstItem();
    }

    /**
     * @param int $id
     *
     * @return User|DataObject
     */
    public function getDesignerById(int $id)
    {
        return $this->initDesignersCollection()
            ->addFieldToFilter('main_table.user_id', ['eq' => $id])
            ->getFirstItem();
    }

    public function getCurrentAuthUser(): ?User
    {
        return $this->authSession->getUser();
    }

    public function getCurrentAuthUserRole(): ?Role
    {
        if (!$this->getCurrentAuthUser()) {
            return null;
        }

        return $this->getCurrentAuthUser()->getRole();
    }

    public function isCurrentAuthUserDesigner(): bool
    {
        if (!$this->getCurrentAuthUserRole()) {
            return false;
        }

        return $this->getCurrentAuthUserRole()->getId() === $this->getDesignerRole()->getId();
    }

    protected function initDesignersCollection(array $data = []): UserCollection
    {
        return $this->designersCollectionFactory->create($data);
    }

    protected function initRoleCollection(array $data = []): RoleCollection
    {
        return $this->roleCollectionFactory->create($data);
    }
}
