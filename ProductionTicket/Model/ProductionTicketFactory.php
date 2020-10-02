<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Model;

use Magento\Framework\ObjectManagerInterface;

class ProductionTicketFactory
{

    protected const DEFAULT_INSTANCE_NAME = '\\Labelin\\ProductionTicket\\Model\\ProductionTicket';

    /** @var string|null  */
    protected $instanceName = null;

    /** @var ObjectManagerInterface  */
    protected $objectManager;

    /**
     * ProductionTicketFactory constructor.
     *
     * @param ObjectManagerInterface $objectManager
     * @param string $instanceName
     */
    public function __construct(ObjectManagerInterface $objectManager, string $instanceName = self::DEFAULT_INSTANCE_NAME)
    {
        $this->objectManager = $objectManager;
        $this->instanceName = $instanceName;
    }

    public function create(array $data = []): ProductionTicket
    {
        return $this->objectManager->create($this->instanceName, $data);
    }
}
