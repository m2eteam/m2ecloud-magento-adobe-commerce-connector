<?php

declare(strict_types=1);

namespace M2E\M2ECloudMagentoConnector\Api\Data;

interface ExtensionInfoInterface
{
    /**#@+
     * Constants defined for keys of array, makes typos less likely
     */
    public const KEY_NAME = 'name';
    public const KEY_VERSION = 'version';
    public const KEY_IS_INIT_COMPLETED = 'is_init_completed';
    /**#@-*/

    /**
     * Get module name
     * @return string
     */
    public function getName(): string;

    /**
     * Set module name
     *
     * @param string $name
     *
     * @return $this
     */
    public function setName(string $name): self;

    /**
     * Get module version
     * @return string
     */
    public function getVersion(): string;

    /**
     * Set module version
     *
     * @param string $version
     *
     * @return $this
     */
    public function setVersion(string $version): self;

    /**
     * Get is module init already completed
     * @return bool
     */
    public function getIsInitCompleted(): bool;

    /**
     * Set is module init already completed
     *
     * @param bool $isInitCompleted
     *
     * @return $this
     */
    public function setIsInitCompleted(bool $isInitCompleted): self;
}
