<?php

declare(strict_types=1);

namespace M2E\M2ECloudMagentoConnector\Model;

class InventoryFactory
{
    private \Magento\Framework\ObjectManagerInterface $objectManager;

    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager
    ) {
        $this->objectManager = $objectManager;
    }

    public function create(): Inventory
    {
        return $this->objectManager->create(Inventory::class);
    }
}
