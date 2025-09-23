<?php

declare(strict_types=1);

namespace M2E\M2ECloudMagentoConnector\Model;

class ModuleInfo implements \M2E\M2ECloudMagentoConnector\Api\Data\ModuleInfoInterface
{
    /** @var \M2E\M2ECloudMagentoConnector\Api\Data\ExtensionInfoInterface[] */
    private array $extensionList;

    /**
     * @param \M2E\M2ECloudMagentoConnector\Api\Data\ExtensionInfoInterface[] $extensionList
     */
    public function __construct(array $extensionList)
    {
        $this->extensionList = $extensionList;
    }

    public function getExtensionList(): array
    {
        return $this->extensionList;
    }
}
