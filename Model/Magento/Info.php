<?php

declare(strict_types=1);

namespace M2E\M2ECloudMagentoConnector\Model\Magento;

class Info implements \M2E\M2ECloudMagentoConnector\Model\ExtensionInterface
{
    private \Magento\Framework\App\ProductMetadataInterface $productMetadata;

    public function __construct(
        \Magento\Framework\App\ProductMetadataInterface $productMetadata
    ) {
        $this->productMetadata = $productMetadata;
    }

    public function getName(): string
    {
        return $this->productMetadata->getName() . ' ' . $this->productMetadata->getEdition();
    }

    public function getVersion(): string
    {
        return $this->productMetadata->getVersion();
    }

    public function isInitCompleted(): bool
    {
        return true;
    }
}
