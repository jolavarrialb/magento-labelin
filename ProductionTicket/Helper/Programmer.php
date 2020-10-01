<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Helper;

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

class Programmer extends AbstractHelper
{
    public const PROGRAMMER_ROLE_NAME = 'Programmer';

    /*** @var UserCollectionFactory */
    protected $programmerCollectionFactory;

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
        $this->programmerCollectionFactory = $userCollectionFactory;
        $this->authSession = $authSession;
    }

    public function getProgrammerCollection(): UserCollection
    {
        return $this->initProgrammerCollection()
            ->addFieldToFilter('is_active', true)
            ->addFieldToFilter('user_role.parent_id', $this->getProgrammerRole()->getId());
    }

    public function getProgrammerEmails(): array
    {
        return $this->getProgrammerCollection()->toArray(['email']);
    }

    /**
     * @return DataObject|Role
     */
    public function getProgrammerRole()
    {
        return $this->initRoleCollection()
            ->setRolesFilter()
            ->addFieldToFilter('role_name', static::PROGRAMMER_ROLE_NAME)
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

    public function isCurrentAuthUserProgrammer(): bool
    {
        if (!$this->getCurrentAuthUserRole()) {
            return false;
        }

        return $this->getCurrentAuthUserRole()->getId() === $this->getProgrammerRole()->getId();
    }

    protected function initProgrammerCollection(array $data = []): UserCollection
    {
        return $this->programmerCollectionFactory->create($data);
    }

    protected function initRoleCollection(array $data = []): RoleCollection
    {
        return $this->roleCollectionFactory->create($data);
    }
}
