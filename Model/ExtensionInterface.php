<?php

declare(strict_types=1);

namespace M2E\M2ECloudMagentoConnector\Model;

interface ExtensionInterface
{
    public function getName(): string;

    public function getVersion(): string;

    public function isInitCompleted(): bool;
}
