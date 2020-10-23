<?php

declare(strict_types=1);

namespace Labelin\Sales\Model\Config\Source;

use Magento\Authorization\Model\Acl\Role\Group;
use Magento\Authorization\Model\ResourceModel\Role\Collection as RoleCollection;
use Magento\Authorization\Model\ResourceModel\Role\CollectionFactory as RoleCollectionFactory;
use Magento\Framework\Data\OptionSourceInterface;
use Magento\User\Model\User;

class RoleAccessOptions implements OptionSourceInterface
{
    /** @var array */
    protected $options = [];

    /*** @var RoleCollection */
    protected $roleCollectionFactory;

    public function __construct(RoleCollectionFactory $roleCollectionFactory)
    {
        $this->roleCollectionFactory = $roleCollectionFactory;
    }

    public function toOptionArray(): array
    {
        if ($this->options) {
            return $this->options;
        }

        foreach ($this->initRoleCollection() as $role) {
            /** @var User $role */

            if ($role->getData('role_type') === Group::ROLE_TYPE) {
                $this->options[] = [
                    'value' => $role->getData('role_id'),
                    'label' => $role->getData('role_name'),
                ];
            }
        }

        return $this->options;
    }

    protected function initRoleCollection(array $data = []): RoleCollection
    {
        return $this->roleCollectionFactory->create($data);
    }
}
