<?php

declare(strict_types=1);

namespace M2E\M2ECloudMagentoConnector\Model\Order\MagentoProcessor;

class InvoiceCreate
{
    private \Magento\Sales\Api\InvoiceOrderInterface $invoiceOrderProcessor;

    public function __construct(
        \Magento\Sales\Api\InvoiceOrderInterface $invoiceOrderProcessor
    ) {
        $this->invoiceOrderProcessor = $invoiceOrderProcessor;
    }

    public function process(\Magento\Sales\Api\Data\OrderInterface $order): void
    {
        if (!$this->canCreateInvoice($order)) {
            return;
        }

        $this->invoiceOrderProcessor->execute((int)$order->getEntityId());
    }

    private function canCreateInvoice(\Magento\Sales\Api\Data\OrderInterface $order): bool
    {
        /** @psalm-suppress UndefinedInterfaceMethod */
        return $order->canInvoice();
    }
}
