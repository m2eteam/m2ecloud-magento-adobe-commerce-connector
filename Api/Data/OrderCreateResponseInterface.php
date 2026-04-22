<?php

declare(strict_types=1);

namespace M2E\M2ECloudMagentoConnector\Api\Data;

interface OrderCreateResponseInterface
{
    public const KEY_IS_SUCCESS = 'is_success';
    public const KEY_ERROR = 'error';
    public const KEY_ENTITY_ID = 'entity_id';

    /**
     * @return bool
     */
    public function isSuccess(): bool;

    /**
     * @param bool $isSuccess
     * @return $this
     */
    public function setIsSuccess(bool $isSuccess);

    /**
     * @return string|null
     */
    public function getError(): ?string;

    /**
     * @param string|null $error
     * @return $this
     */
    public function setError(?string $error);

    /**
     * @return int|null
     */
    public function getEntityId(): ?int;

    /**
     * @param int|null $entityId
     * @return $this
     */
    public function setEntityId(?int $entityId);
}
