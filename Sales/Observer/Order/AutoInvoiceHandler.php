<?php

declare(strict_types=1);

namespace Labelin\Sales\Observer\Order;

use Labelin\Sales\Model\Order;
use Magento\Framework\DB\Transaction;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Model\Order\Invoice;
use Magento\Sales\Model\Service\InvoiceService;

class AutoInvoiceHandler implements ObserverInterface
{
    /** @var InvoiceService */
    protected $invoiceService;

    /** @var Transaction */
    protected $transaction;

    public function __construct(
        InvoiceService $invoiceService,
        Transaction $transaction
    ) {
        $this->invoiceService = $invoiceService;

        $this->transaction = $transaction;
    }

    /**
     * @param Observer $observer
     *
     * @return $this
     * @throws LocalizedException
     * @throws \Exception
     */
    public function execute(Observer $observer): self
    {
        /** @var Order $order */
        $order = $observer->getEvent()->getData('order');

        if ($order->canInvoice()) {
            $invoice = $this->invoiceService->prepareInvoice($order);
            $invoice->setRequestedCaptureCase(Invoice::CAPTURE_ONLINE);
            $invoice->register();

            $this->transaction
                ->addObject($invoice)
                ->addObject($invoice->getOrder())
                ->save();
        }

        return $this;
    }
}
