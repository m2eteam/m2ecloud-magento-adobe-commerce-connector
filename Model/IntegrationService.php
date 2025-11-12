<?php

declare(strict_types=1);

namespace M2E\M2ECloudMagentoConnector\Model;

class IntegrationService
{
    private const INTEGRATION_ID_CONFIG_PATH = 'm2e/m2e_m2ecloud_magento_connector/integration_id';

    private \Magento\Framework\App\Config\ScopeConfigInterface $config;
    private \Magento\Integration\Api\IntegrationServiceInterface $integrationService;
    private \Magento\Framework\App\ResourceConnection $resourceConnection;
    private \Magento\Framework\App\CacheInterface $appCache;
    private \M2E\M2ECloudMagentoConnector\Model\IntegrationFactory $integrationFactory;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $config,
        \Magento\Integration\Api\IntegrationServiceInterface $integrationService,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \Magento\Framework\App\CacheInterface $appCache,
        \M2E\M2ECloudMagentoConnector\Model\IntegrationFactory $integrationFactory
    ) {
        $this->config = $config;
        $this->integrationService = $integrationService;
        $this->resourceConnection = $resourceConnection;
        $this->appCache = $appCache;
        $this->integrationFactory = $integrationFactory;
    }

    public function isIntegrationExist(): bool
    {
        try {
            $integrationId = $this->getIntegrationId();
        } catch (\Exception $e) {
            return false;
        }

        try {
            $integration = $this->integrationService->get($integrationId);

            return (bool)$integration->getId();
        } catch (\Magento\Framework\Exception\IntegrationException $e) {
            return false;
        }
    }

    /**
     * @return \M2E\M2ECloudMagentoConnector\Model\Integration
     * @throws \Magento\Framework\Exception\IntegrationException
     */
    public function integrationCreate(): \M2E\M2ECloudMagentoConnector\Model\Integration
    {
        $integration = $this->createMagentoIntegration();
        $this->appCache->clean([\Magento\Backend\Block\Menu::CACHE_TAGS, 'CONFIG']);

        return $this->integrationFactory->createByMagentoIntegration($integration);
    }

    /**
     * @return \Magento\Integration\Model\Integration
     * @throws \Magento\Framework\Exception\IntegrationException
     */
    public function createMagentoIntegration(): \Magento\Integration\Model\Integration
    {
        $integration = $this->integrationService->create([
            'name' => 'M2E Cloud Connector',
            'resource' => $this->getIntegrationPermissions(),
        ]);

        $this->insertConfigData((int)$integration->getId());

        return $integration;
    }

    /**
     * @return \M2E\M2ECloudMagentoConnector\Model\Integration
     * @throws \Exception
     */
    public function getIntegration(): \M2E\M2ECloudMagentoConnector\Model\Integration
    {
        return $this->integrationFactory->createById(
            $this->getIntegrationId()
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

    private function insertConfigData(int $integrationId): void
    {
        $connection = $this->resourceConnection->getConnection();
        $connection->insertOnDuplicate(
            $this->resourceConnection->getTableName('core_config_data'),
            [
                'path' => self::INTEGRATION_ID_CONFIG_PATH,
                'value' => $integrationId,
            ],
            ['value']
        );
    }

    private function getIntegrationPermissions(): array
    {
        return [
            'Magento_Backend::admin',
            'Magento_Cart::cart',
            'Magento_Cart::manage',
            'Magento_Catalog::catalog',
            'Magento_Catalog::catalog_inventory',
            'Magento_Catalog::categories',
            'Magento_Catalog::products',
            'Magento_Catalog::attributes_attributes',
            'Magento_Catalog::sets',
            'Magento_PaymentServicesDashboard::paymentservices',
            'Magento_PaymentServicesDashboard::paymentservicesdashboard',
            'Magento_PaymentServicesPaypal::ordercreate',
            'Magento_Sales::actions',
            'Magento_Sales::actions_edit',
            'Magento_Sales::actions_view',
            'Magento_Sales::api_actions',
            'Magento_Sales::cancel',
            'Magento_Sales::capture',
            'Magento_Sales::comment',
            'Magento_Sales::create',
            'Magento_Sales::creditmemo',
            'Magento_Sales::email',
            'Magento_Sales::emails',
            'Magento_Sales::hold',
            'Magento_Sales::invoice',
            'Magento_Sales::reorder',
            'Magento_Sales::review_payment',
            'Magento_Sales::sales',
            'Magento_Sales::sales_creditmemo',
            'Magento_Sales::sales_invoice',
            'Magento_Sales::sales_operation',
            'Magento_Sales::sales_order',
            'Magento_Sales::ship',
            'Magento_Sales::shipment',
            'Magento_Sales::transactions',
            'Magento_Sales::transactions_fetch',
            'Magento_Sales::unhold',
            'Magento_ServiceProxy::services',
            'M2E_M2ECloudMagentoConnector::inventories',
            'M2E_M2ECloudMagentoConnector::create_order',
            'M2E_M2ECloudMagentoConnector::module_info',
        ];
    }
}
