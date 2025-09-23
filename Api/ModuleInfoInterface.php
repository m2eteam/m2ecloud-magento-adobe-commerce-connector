<?php

declare(strict_types=1);

namespace M2E\M2ECloudMagentoConnector\Api;

interface ModuleInfoInterface
{
    /**
     * Returns active module list info
     * @return \M2E\M2ECloudMagentoConnector\Api\Data\ModuleInfoInterface
     */
    public function getInfo(): \M2E\M2ECloudMagentoConnector\Api\Data\ModuleInfoInterface;
}
