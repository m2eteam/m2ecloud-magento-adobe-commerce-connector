<?php

declare(strict_types=1);

namespace M2E\M2ECloudMagentoConnector\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;

class AddM2eCloudIntegration implements DataPatchInterface
{
    private \Magento\Framework\Setup\ModuleDataSetupInterface $moduleDataSetup;
    private \M2E\M2ECloudMagentoConnector\Model\IntegrationService $integrationService;

    public function __construct(
        \Magento\Framework\Setup\ModuleDataSetupInterface $moduleDataSetup,
        \M2E\M2ECloudMagentoConnector\Model\IntegrationService $integrationService
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->integrationService = $integrationService;
    }

    public function apply(): void
    {
        $this->moduleDataSetup->startSetup();

        $this->integrationService->createMagentoIntegration();

        $this->moduleDataSetup->endSetup();
    }

    public static function getDependencies(): array
    {
        return [];
    }

    public function getAliases(): array
    {
        return [];
    }
}
