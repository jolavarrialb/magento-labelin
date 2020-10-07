<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Controller\Adminhtml\Grid;

use Labelin\ProductionTicket\Helper\Acl as AclHelper;
use Labelin\ProductionTicket\Model\ProductionTicket;
use Labelin\ProductionTicket\Model\ProductionTicketRepository;
use Labelin\ProductionTicket\Model\ResourceModel\ProductionTicket\CollectionFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\Component\MassAction\Filter;
use Psr\Log\LoggerInterface;

abstract class AbstractComplete extends Action
{
    /** @var Filter */
    protected $filter;

    /** @var CollectionFactory */
    protected $collectionFactory;

    /** @var ProductionTicketRepository */
    protected $productionTicketRepository;

    /*** @var LoggerInterface */
    private $logger;

    /** @var AclHelper */
    protected $aclHelper;

    /** @var string */
    protected $successMessage;

    /** @var string */
    protected $errorMessage;

    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        ProductionTicketRepository $productionTicketRepository,
        AclHelper $aclHelper,
        LoggerInterface $logger = null,
        string $successMessage = '',
        string $errorMessage = ''
    ) {
        parent::__construct($context);

        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->productionTicketRepository = $productionTicketRepository;

        $this->aclHelper = $aclHelper;

        $this->logger = $logger ?: ObjectManager::getInstance()->create(LoggerInterface::class);

        $this->successMessage = $successMessage;
        $this->errorMessage = $errorMessage;
    }

    abstract public function getStatus(): bool;

    /**
     * @return ResponseInterface|ResultInterface
     * @throws LocalizedException
     */
    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());

        $ticketsCompleted = 0;
        $ticketsCompletedError = 0;

        foreach ($collection as $ticket) {
            /** @var ProductionTicket $ticket */
            try {
                $ticket = $this->productionTicketRepository->get((int)$ticket->getId());
                $ticket->setStatus($this->getStatus());
                $this->productionTicketRepository->save($ticket);
                $ticketsCompleted++;
            } catch (LocalizedException $exception) {
                $this->logger->error($exception->getLogMessage());
                $ticketsCompletedError++;
            }
        }

        if ($ticketsCompleted) {
            $this->messageManager->addSuccessMessage(__($this->successMessage, $ticketsCompleted));
        }

        if ($ticketsCompletedError) {
            $this->messageManager->addErrorMessage(__($this->errorMessage, $ticketsCompletedError));
        }

        return $this->_redirect($this->_redirect->getRefererUrl());
    }

    protected function _isAllowed(): bool
    {
        return parent::_isAllowed() && $this->aclHelper->isAllowedAclProductionTicketGrid();
    }
}
