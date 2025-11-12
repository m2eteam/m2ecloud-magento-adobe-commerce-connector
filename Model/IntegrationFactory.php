<?php

declare(strict_types=1);

namespace M2E\M2ECloudMagentoConnector\Model;

class IntegrationFactory
{
    private \Magento\Framework\ObjectManagerInterface $objectManager;
    private \Magento\Integration\Api\IntegrationServiceInterface $magentoIntegrationService;

    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Integration\Api\IntegrationServiceInterface $magentoIntegrationService
    ) {
        $this->objectManager = $objectManager;
        $this->magentoIntegrationService = $magentoIntegrationService;
    }

    public function createById(int $id): \M2E\M2ECloudMagentoConnector\Model\Integration
    {
        $integration = $this->magentoIntegrationService->get($id);

        return $this->createByMagentoIntegration($integration);
    }

    public function createByMagentoIntegration(
        \Magento\Integration\Model\Integration $integration
    ): \M2E\M2ECloudMagentoConnector\Model\Integration {
        return $this->objectManager->create(
            \M2E\M2ECloudMagentoConnector\Model\Integration::class,
            ['integration' => $integration]
        );
    }
}
