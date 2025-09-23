<?php

declare(strict_types=1);

namespace M2E\M2ECloudMagentoConnector\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;

class AddM2eCloudIntegration implements DataPatchInterface
{
    private \Magento\Framework\Setup\ModuleDataSetupInterface $moduleDataSetup;
    private \Magento\Integration\Api\IntegrationServiceInterface $integrationService;

    public function __construct(
        \Magento\Framework\Setup\ModuleDataSetupInterface $moduleDataSetup,
        \Magento\Integration\Api\IntegrationServiceInterface $integrationService
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->integrationService = $integrationService;
    }

    public function apply(): void
    {
        $this->moduleDataSetup->startSetup();
        $integration = $this->integrationService->create([
            'name' => 'M2E Cloud Connector',
            'resource' => $this->getIntegrationPermissions(),
        ]);

        $this->insertConfigData((int)$integration->getId());

        $this->moduleDataSetup->endSetup();
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

    private function insertConfigData(int $integrationId): void
    {
        $this->moduleDataSetup->getConnection()->insert(
            $this->moduleDataSetup->getTable('core_config_data'),
            [
                'path' => \M2E\M2ECloudMagentoConnector\Model\IntegrationFactory::INTEGRATION_ID_CONFIG_PATH,
                'value' => $integrationId,
            ]
        );
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
