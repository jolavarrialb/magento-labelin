<?php

declare(strict_types=1);

namespace Labelin\Sales\Helper;

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

class Shipper extends AbstractHelper
{
    public const SHIPPER_ROLE_NAME = 'Shipper';

    /*** @var UserCollectionFactory */
    protected $shippersCollectionFactory;

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
        $this->shippersCollectionFactory = $userCollectionFactory;
        $this->authSession = $authSession;
    }

    public function getShippersCollection(): UserCollection
    {
        return $this->initShippersCollection()
            ->addFieldToFilter('is_active', true)
            ->addFieldToFilter('user_role.parent_id', $this->getShipperRole()->getId());
    }

    /**
     * @return DataObject|Role
     */
    public function getShipperRole()
    {
        return $this->initRoleCollection()
            ->setRolesFilter()
            ->addFieldToFilter('role_name', static::SHIPPER_ROLE_NAME)
            ->getFirstItem();
    }

    /**
     * @param int $id
     *
     * @return User|DataObject
     */
    public function getShipperById(int $id)
    {
        return $this->initShippersCollection()
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

    public function isCurrentAuthUserShipper(): bool
    {
        if (!$this->getCurrentAuthUserRole()) {
            return false;
        }

        return $this->getCurrentAuthUserRole()->getId() === $this->getShipperRole()->getId();
    }

    protected function initShippersCollection(array $data = []): UserCollection
    {
        return $this->shippersCollectionFactory->create($data);
    }

    protected function initRoleCollection(array $data = []): RoleCollection
    {
        return $this->roleCollectionFactory->create($data);
    }
}
