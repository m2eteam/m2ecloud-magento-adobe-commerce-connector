<?php

declare(strict_types=1);

namespace M2E\M2ECloudMagentoConnector\Model;

class OrderManagement implements \M2E\M2ECloudMagentoConnector\Api\OrderManagementInterface
{
    private \M2E\M2ECloudMagentoConnector\Model\Order\Builder $orderBuilder;
    private \Psr\Log\LoggerInterface $logger;

    public function __construct(
        \M2E\M2ECloudMagentoConnector\Model\Order\Builder $orderBuilder,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->orderBuilder = $orderBuilder;
        $this->logger = $logger;
    }

    /**
     * @inheirtDoc
     * @throws \Exception
     */
    public function createOrder(\M2E\M2ECloudMagentoConnector\Api\Data\OrderInformationInterface $orderInformation)
    {
        try {
            $order = $this->orderBuilder->processOrder($orderInformation);
        } catch (\Throwable $e) {
            $exceptionMessage = 'Error while creating order: ' . $e->getMessage();
            $this->logger->error(
                $exceptionMessage,
                ['exception' => $e->getTraceAsString()]
            );

            throw new \Exception($exceptionMessage, $e->getCode(), $e);
        }

        return $order;
    }
}
