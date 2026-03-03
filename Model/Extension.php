<?php

declare(strict_types=1);

namespace M2E\M2ECloudMagentoConnector\Model;

class Extension implements \M2E\M2ECloudMagentoConnector\Model\ExtensionInterface
{
    private \Magento\Framework\Module\ModuleListInterface $moduleList;

    public function __construct(
        \Magento\Framework\Module\ModuleListInterface $moduleList
    ) {
        $this->moduleList = $moduleList;
    }

    public function getName(): string
    {
        return \M2E\M2ECloudMagentoConnector\Helper\Module::IDENTIFIER;
    }

    public function getVersion(): string
    {
        $module = $this->moduleList->getOne(\M2E\M2ECloudMagentoConnector\Helper\Module::IDENTIFIER);

        return $module ? $module['setup_version'] : '';
    }

    public function isInitCompleted(): bool
    {
        return true;
    }
}
