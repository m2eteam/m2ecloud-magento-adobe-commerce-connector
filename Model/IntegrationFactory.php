<?php

declare(strict_types=1);

namespace M2E\M2ECloudMagentoConnector\Model;

class IntegrationFactory
{
    public const INTEGRATION_ID_CONFIG_PATH = 'm2e/m2e_m2ecloud_magento_connector/integration_id';

    private \Magento\Framework\ObjectManagerInterface $objectManager;
    private \Magento\Framework\App\Config\ScopeConfigInterface $config;
    private \Magento\Integration\Api\IntegrationServiceInterface $integrationService;

    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $config,
        \Magento\Integration\Api\IntegrationServiceInterface $integrationService
    ) {
        $this->objectManager = $objectManager;
        $this->config = $config;
        $this->integrationService = $integrationService;
    }

    public function create(): \M2E\M2ECloudMagentoConnector\Model\Integration
    {
        $integration = $this->integrationService->get($this->getIntegrationId());

        return $this->objectManager->create(
            \M2E\M2ECloudMagentoConnector\Model\Integration::class,
            ['integration' => $integration]
        );
    }

    private function getIntegrationId(): int
    {
        $integrationId = $this->config->getValue(self::INTEGRATION_ID_CONFIG_PATH);
        if (!$integrationId) {
            throw new \Exception((string)__('The M2E Cloud Connector Integration has not been installed yet.'));
        }

        return (int)$integrationId;
    }
}
