<?php

declare(strict_types=1);

namespace M2E\M2ECloudMagentoConnector\Model\Magento\Product\Inventory;

use M2E\M2ECloudMagentoConnector\Model\Magento\Product\Inventory;
use M2E\M2ECloudMagentoConnector\Model\MSI\Magento\Product\Inventory as MSIInventory;

class Factory
{
    private \Magento\Framework\ObjectManagerInterface $objectManager;
    private \M2E\M2ECloudMagentoConnector\Model\MSIChecker $MSIChecker;

    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \M2E\M2ECloudMagentoConnector\Model\MSIChecker $MSIChecker
    ) {
        $this->objectManager = $objectManager;
        $this->MSIChecker = $MSIChecker;
    }

    public function getObject(
        \Magento\Catalog\Model\Product $product
    ): \M2E\M2ECloudMagentoConnector\Model\Magento\Product\Inventory\AbstractModel {
        /** @var \M2E\M2ECloudMagentoConnector\Model\Magento\Product\Inventory\AbstractModel $object */
        $object = $this->objectManager->get(
            $this->MSIChecker->isMSISupportingVersion()
                ? MSIInventory::class
                : Inventory::class
        );
        $object->setProduct($product);

        return $object;
    }
}
