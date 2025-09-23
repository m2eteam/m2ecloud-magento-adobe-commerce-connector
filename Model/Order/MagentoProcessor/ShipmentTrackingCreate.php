<?php

declare(strict_types=1);

namespace M2E\M2ECloudMagentoConnector\Model\Order\MagentoProcessor;

class ShipmentTrackingCreate
{
    private \Magento\Sales\Api\ShipmentTrackRepositoryInterface $shipmentTrackRepository;
    private \Magento\Sales\Api\Data\ShipmentTrackInterfaceFactory $shipmentTrackFactory;

    public function __construct(
        \Magento\Sales\Api\ShipmentTrackRepositoryInterface $shipmentTrackRepository,
        \Magento\Sales\Api\Data\ShipmentTrackInterfaceFactory $shipmentTrackFactory
    ) {
        $this->shipmentTrackRepository = $shipmentTrackRepository;
        $this->shipmentTrackFactory = $shipmentTrackFactory;
    }

    public function process(
        int $orderId,
        \M2E\M2ECloudMagentoConnector\Api\Data\ShippingInformationInterface $shippingInformation,
        int $shippingId
    ): void {
        $shipmentTrack = $this->shipmentTrackFactory->create();
        $shipmentTrack->setOrderId($orderId);
        $shipmentTrack->setParentId($shippingId);
        $shipmentTrack->setTrackNumber($shippingInformation->getTrackingNumber());
        $shipmentTrack->setCarrierCode(\Magento\Sales\Model\Order\Shipment\Track::CUSTOM_CARRIER_CODE);
        $shipmentTrack->setTitle($shippingInformation->getTrackingTitle());

        $this->shipmentTrackRepository->save($shipmentTrack);
    }
}
