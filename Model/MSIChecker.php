<?php

declare(strict_types=1);

namespace M2E\M2ECloudMagentoConnector\Model;

use Magento\InventoryConfigurationApi\Model\IsSourceItemManagementAllowedForProductTypeInterface;

class MSIChecker
{
    public const MAGENTO_INVENTORY_MODULE_CODE = 'Magento_Inventory';

    private \Magento\Framework\Module\ModuleListInterface $moduleList;
    private \Magento\Framework\ObjectManagerInterface $objectManager;
    private bool $isMSISupporting;

    public function __construct(
        \Magento\Framework\Module\ModuleListInterface $moduleList,
        \Magento\Framework\ObjectManagerInterface $objectManager
    ) {
        $this->moduleList = $moduleList;
        $this->objectManager = $objectManager;
    }

    public function isMSISupportingVersion(): bool
    {
        /** @psalm-suppress RedundantPropertyInitializationCheck */
        if (!isset($this->isMSISupporting)) {
            $this->isMSISupporting = $this->checkMSIModule();
        }

        return $this->isMSISupporting;
    }

    private function checkMSIModule(): bool
    {
        if ($this->moduleList->getOne(self::MAGENTO_INVENTORY_MODULE_CODE) === null) {
            return false;
        }

        if (interface_exists(IsSourceItemManagementAllowedForProductTypeInterface::class)) {
            $isSourceItemManagementAllowedForProductType = $this->objectManager->get(
                IsSourceItemManagementAllowedForProductTypeInterface::class,
            );

            return $isSourceItemManagementAllowedForProductType->execute('simple');//check only simple products
        }

        return true;
    }
}
