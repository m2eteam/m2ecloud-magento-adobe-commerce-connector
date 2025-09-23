<?php

declare(strict_types=1);

namespace M2E\M2ECloudMagentoConnector\Model\Order;

use Magento\Sales\Api\Data\OrderInterface;

class Builder
{
    private \Magento\Sales\Api\OrderRepositoryInterface $orderRepository;
    private \M2E\M2ECloudMagentoConnector\Model\Order\MagentoProcessor\InvoiceCreate $invoiceCreate;
    private \M2E\M2ECloudMagentoConnector\Model\Order\MagentoProcessor\OrderSubmit $orderSubmit;
    private MagentoProcessor\ShipmentCreate $shipmentCreate;

    public function __construct(
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        \M2E\M2ECloudMagentoConnector\Model\Order\MagentoProcessor\InvoiceCreate $invoiceCreate,
        \M2E\M2ECloudMagentoConnector\Model\Order\MagentoProcessor\OrderSubmit $orderSubmit,
        \M2E\M2ECloudMagentoConnector\Model\Order\MagentoProcessor\ShipmentCreate $shipmentCreate
    ) {
        $this->orderRepository = $orderRepository;
        $this->invoiceCreate = $invoiceCreate;
        $this->orderSubmit = $orderSubmit;
        $this->shipmentCreate = $shipmentCreate;
    }

    public function processOrder(
        \M2E\M2ECloudMagentoConnector\Api\Data\OrderInformationInterface $orderInformation
    ): OrderInterface {
        $order = $this->orderSubmit->process($orderInformation);
        $this->invoiceCreate->process($order);

        $shippingInformation = $orderInformation->getShippingInformation();
        if ($shippingInformation && $shippingInformation->getIsShipped()) {
            $shippingId = $this->shipmentCreate->process(
                (int)$order->getEntityId(),
                $shippingInformation
            );
        }

        /** @psalm-suppress UndefinedInterfaceMethod */
        $this->orderRepository->_resetState();
        $order = $this->orderRepository->get($order->getEntityId());

        if (isset($shippingId)) {
            $this->addOrderExtensionAttributes($order, $shippingId);
        }

        return $order;
    }

    private function addOrderExtensionAttributes(OrderInterface $order, int $shippingId)
    {
        $extensionAttributes = $order->getExtensionAttributes();
        $extensionAttributes->setShipmentId($shippingId);
        $order->setExtensionAttributes($extensionAttributes);
    }
}
