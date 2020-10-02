<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Model;

use Magento\Framework\ObjectManagerInterface;

class ProductionTicketFactory
{
    /** @var string */
    protected $instanceName;

    /** @var ObjectManagerInterface */
    protected $objectManager;

    public function __construct(
        ObjectManagerInterface $objectManager,
        string $instanceName = ProductionTicket::class
    ) {
        $this->objectManager = $objectManager;
        $this->instanceName = $instanceName;
    }

    public function create(array $data = []): ProductionTicket
    {
        return $this->objectManager->create($this->instanceName, $data);
    }
}
