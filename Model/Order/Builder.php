<?php

declare(strict_types=1);

namespace M2E\M2ECloudMagentoConnector\Model\Order;

class Builder
{
    private \M2E\M2ECloudMagentoConnector\Model\Order\MagentoProcessor\InvoiceCreate $invoiceCreate;
    private \M2E\M2ECloudMagentoConnector\Model\Order\MagentoProcessor\OrderSubmit $orderSubmit;
    private MagentoProcessor\ShipmentCreate $shipmentCreate;

    public function __construct(
        \M2E\M2ECloudMagentoConnector\Model\Order\MagentoProcessor\InvoiceCreate $invoiceCreate,
        \M2E\M2ECloudMagentoConnector\Model\Order\MagentoProcessor\OrderSubmit $orderSubmit,
        \M2E\M2ECloudMagentoConnector\Model\Order\MagentoProcessor\ShipmentCreate $shipmentCreate
    ) {
        $this->invoiceCreate = $invoiceCreate;
        $this->orderSubmit = $orderSubmit;
        $this->shipmentCreate = $shipmentCreate;
    }

    public function processOrder(
        \M2E\M2ECloudMagentoConnector\Api\Data\OrderInformationInterface $orderInformation
    ): int {
        $order = $this->orderSubmit->process($orderInformation);
        $this->invoiceCreate->process($order);

        $shippingInformation = $orderInformation->getShippingInformation();
        if ($shippingInformation && $shippingInformation->getIsShipped()) {
            $this->shipmentCreate->process(
                (int)$order->getEntityId(),
                $shippingInformation
            );
        }

        return (int)$order->getEntityId();
    }
}
