<?php

declare(strict_types=1);

namespace M2E\M2ECloudMagentoConnector\Api\Data;

interface ModuleInfoInterface
{
    /**
     * @return \M2E\M2ECloudMagentoConnector\Api\Data\ExtensionInfoInterface[]
     */
    public function getExtensionList(): array;
}
