<?php

declare(strict_types=1);

namespace M2E\M2ECloudMagentoConnector\Model\Magento\Quote;

class BuilderFactory
{
    private \Magento\Framework\ObjectManagerInterface $objectManager;

    public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public function create(
        \M2E\M2ECloudMagentoConnector\Model\Order\ProxyObject $proxyOrder,
        array $data = []
    ): \M2E\M2ECloudMagentoConnector\Model\Magento\Quote\Builder {
        $data['proxyOrder'] = $proxyOrder;

        return $this->objectManager->create(\M2E\M2ECloudMagentoConnector\Model\Magento\Quote\Builder::class, $data);
    }
}
