<?php

declare(strict_types=1);

namespace M2E\M2ECloudMagentoConnector\Model\Order\MagentoProcessor;

class ShipmentCreate
{
    private \Magento\Sales\Api\ShipOrderInterface $shipOrderProcessor;
    private \M2E\M2ECloudMagentoConnector\Model\Order\MagentoProcessor\ShipmentTrackingCreate $shipmentTrackingCreate;

    public function __construct(
        \Magento\Sales\Api\ShipOrderInterface $shipOrderProcessor,
        \M2E\M2ECloudMagentoConnector\Model\Order\MagentoProcessor\ShipmentTrackingCreate $shipmentTrackingCreate
    ) {
        $this->shipOrderProcessor = $shipOrderProcessor;
        $this->shipmentTrackingCreate = $shipmentTrackingCreate;
    }

    public function process(
        int $orderId,
        \M2E\M2ECloudMagentoConnector\Api\Data\ShippingInformationInterface $shippingInformation
    ): int {
        $shipmentId = (int)$this->shipOrderProcessor->execute($orderId);
        if ($shippingInformation->getTrackingNumber()) {
            $this->shipmentTrackingCreate->process(
                $orderId,
                $shippingInformation,
                $shipmentId
            );
        }

        return $shipmentId;
    }
}
