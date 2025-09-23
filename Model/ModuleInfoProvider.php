<?php

declare(strict_types=1);

namespace M2E\M2ECloudMagentoConnector\Model;

class ModuleInfoProvider implements \M2E\M2ECloudMagentoConnector\Api\ModuleInfoInterface
{
    /** @var \M2E\M2ECloudMagentoConnector\Model\ExtensionInterface[] */
    private array $extensionList;

    /**
     * @param \M2E\M2ECloudMagentoConnector\Model\ExtensionInterface[] $extensionList
     */
    public function __construct(
        array $extensionList = []
    ) {
        $this->extensionList = $extensionList;
    }

    /**
     * @inheirtDoc
     */
    public function getInfo(): \M2E\M2ECloudMagentoConnector\Api\Data\ModuleInfoInterface
    {
        $extensions = [];
        foreach ($this->extensionList as $extension) {
            $module = new ExtensionInfo();
            $module->setName($extension->getName());
            $module->setVersion($extension->getVersion());
            $module->setIsInitCompleted($extension->isInitCompleted());

            $extensions[] = $module;
        }

        return new ModuleInfo($extensions);
    }
}
