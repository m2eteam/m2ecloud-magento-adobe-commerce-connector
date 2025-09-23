<?php

declare(strict_types=1);

namespace M2E\M2ECloudMagentoConnector\Api;

interface OrderManagementInterface
{
    /**
     * Create order
     *
     * @param \M2E\M2ECloudMagentoConnector\Api\Data\OrderInformationInterface $orderInformation
     *
     * @return \Magento\Sales\Api\Data\OrderInterface
     * @api
     */
    public function createOrder(\M2E\M2ECloudMagentoConnector\Api\Data\OrderInformationInterface $orderInformation);
}
