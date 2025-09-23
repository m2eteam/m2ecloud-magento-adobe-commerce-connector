<?php

declare(strict_types=1);

namespace M2E\M2ECloudMagentoConnector\Model\Order;

class ProxyObjectFactory
{
    private \Magento\Framework\ObjectManagerInterface $objectManager;

    public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public function create(
        \M2E\M2ECloudMagentoConnector\Api\Data\OrderInformationInterface $orderInformation,
        array $data = []
    ): ProxyObject {
        $data['orderInformation'] = $orderInformation;

        return $this->objectManager->create(ProxyObject::class, $data);
    }
}
