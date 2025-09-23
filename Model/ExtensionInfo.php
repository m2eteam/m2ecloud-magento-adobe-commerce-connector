<?php

declare(strict_types=1);

namespace M2E\M2ECloudMagentoConnector\Model;

use Magento\Framework\DataObject;

class ExtensionInfo extends DataObject implements \M2E\M2ECloudMagentoConnector\Api\Data\ExtensionInfoInterface
{
    public function getName(): string
    {
        return $this->getData(self::KEY_NAME);
    }

    public function setName(string $name): self
    {
        return $this->setData(self::KEY_NAME, $name);
    }

    public function getVersion(): string
    {
        return $this->getData(self::KEY_VERSION);
    }

    public function setVersion(string $version): self
    {
        return $this->setData(self::KEY_VERSION, $version);
    }

    public function getIsInitCompleted(): bool
    {
        return $this->getData(self::KEY_IS_INIT_COMPLETED);
    }

    public function setIsInitCompleted(
        bool $isInitCompleted
    ): self {
        return $this->setData(self::KEY_IS_INIT_COMPLETED, $isInitCompleted);
    }
}
