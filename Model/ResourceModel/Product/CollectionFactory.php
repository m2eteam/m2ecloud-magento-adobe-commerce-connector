<?php

declare(strict_types=1);

namespace M2E\M2ECloudMagentoConnector\Model\ResourceModel\Product;

class CollectionFactory
{
    private \Magento\Framework\ObjectManagerInterface $objectManager;

    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager
    ) {
        $this->objectManager = $objectManager;
    }

    public function create(array $data = []): \M2E\M2ECloudMagentoConnector\Model\ResourceModel\Product\Collection
    {
        return $this->objectManager->create(
            \M2E\M2ECloudMagentoConnector\Model\ResourceModel\Product\Collection::class,
            $data
        );
    }
}
