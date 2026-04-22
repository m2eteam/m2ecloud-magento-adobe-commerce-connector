<?php

declare(strict_types=1);

namespace M2E\M2ECloudMagentoConnector\Model;

class OrderManagement implements \M2E\M2ECloudMagentoConnector\Api\OrderManagementInterface
{
    private \M2E\M2ECloudMagentoConnector\Model\Order\CreateResponseFactory $responseFactory;
    private \M2E\M2ECloudMagentoConnector\Model\Order\Builder $orderBuilder;
    private \Psr\Log\LoggerInterface $logger;

    public function __construct(
        \M2E\M2ECloudMagentoConnector\Model\Order\CreateResponseFactory $responseFactory,
        \M2E\M2ECloudMagentoConnector\Model\Order\Builder $orderBuilder,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->orderBuilder = $orderBuilder;
        $this->logger = $logger;
        $this->responseFactory = $responseFactory;
    }

    /**
     * @inheirtDoc
     */
    public function createOrder(\M2E\M2ECloudMagentoConnector\Api\Data\OrderInformationInterface $orderInformation)
    {
        $response = $this->responseFactory->create();
        try {
            $orderId = $this->orderBuilder->processOrder($orderInformation);
            $response->setEntityId($orderId);
        } catch (\Throwable $e) {
            $this->logger->error(
                'Error while creating order: ' . $e->getMessage(),
                ['exception' => $e->getTraceAsString()]
            );
            $response->setError($e->getMessage());
        }

        return $response;
    }
}
