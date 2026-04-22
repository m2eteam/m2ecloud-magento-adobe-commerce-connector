<?php

declare(strict_types=1);

namespace M2E\M2ECloudMagentoConnector\Model\Order;

use M2E\M2ECloudMagentoConnector\Api\Data\OrderCreateResponseInterface;

class CreateResponse extends \Magento\Framework\DataObject implements OrderCreateResponseInterface
{
    /**
     * {@inheritdoc}
     */
    public function isSuccess(): bool
    {
        return (bool)$this->getData(self::KEY_IS_SUCCESS);
    }

    /**
     * {@inheritdoc}
     */
    public function setIsSuccess(bool $isSuccess)
    {
        return $this->setData(self::KEY_IS_SUCCESS, $isSuccess);
    }

    /**
     * {@inheritdoc}
     */
    public function getError(): ?string
    {
        $error = $this->getData(self::KEY_ERROR);

        return $error === null ? null : (string)$error;
    }

    /**
     * {@inheritdoc}
     */
    public function setError(?string $error)
    {
        $this->setIsSuccess(false);

        return $this->setData(self::KEY_ERROR, $error);
    }

    /**
     * {@inheritdoc}
     */
    public function getEntityId(): ?int
    {
        $entityId = $this->getData(self::KEY_ENTITY_ID);

        return $entityId === null ? null : (int)$entityId;
    }

    /**
     * {@inheritdoc}
     */
    public function setEntityId(?int $entityId)
    {
        $this->setIsSuccess(true);

        return $this->setData(self::KEY_ENTITY_ID, $entityId);
    }
}
