<?php

declare(strict_types=1);

namespace M2E\M2ECloudMagentoConnector\Plugin\Sales\OrderRepository;

class AddShipmentId
{
    private \Magento\Sales\Api\Data\OrderExtensionFactory $orderExtensionFactory;
    private \Magento\Sales\Api\ShipmentRepositoryInterface $shipmentRepository;
    private \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder;

    public function __construct(
        \Magento\Sales\Api\Data\OrderExtensionFactory $orderExtensionFactory,
        \Magento\Sales\Api\ShipmentRepositoryInterface $shipmentRepository,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->orderExtensionFactory = $orderExtensionFactory;
        $this->shipmentRepository = $shipmentRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    public function afterGet(
        \Magento\Sales\Api\OrderRepositoryInterface $subject,
        \Magento\Sales\Api\Data\OrderInterface $order
    ): \Magento\Sales\Api\Data\OrderInterface {
        $this->addShipmentId($order);

        return $order;
    }

    public function afterGetList(
        \Magento\Sales\Api\OrderRepositoryInterface $subject,
        \Magento\Sales\Api\Data\OrderSearchResultInterface $orderSearchResult
    ): \Magento\Sales\Api\Data\OrderSearchResultInterface {
        foreach ($orderSearchResult->getItems() as $order) {
            $this->addShipmentId($order);
        }

        return $orderSearchResult;
    }

    private function addShipmentId(\Magento\Sales\Api\Data\OrderInterface $order): void
    {
        $this->searchCriteriaBuilder->addFilter('order_id', $order->getEntityId());
        $shipments = $this->shipmentRepository->getList($this->searchCriteriaBuilder->create());

        if ($shipments->getTotalCount() > 0) {
            $items = $shipments->getItems();
            $shipment = reset($items);

            $extensionAttributes = $order->getExtensionAttributes();
            if ($extensionAttributes === null) {
                $extensionAttributes = $this->orderExtensionFactory->create();
            }

            $extensionAttributes->setShipmentId((int)$shipment->getEntityId());
            $order->setExtensionAttributes($extensionAttributes);
        }
    }
}
