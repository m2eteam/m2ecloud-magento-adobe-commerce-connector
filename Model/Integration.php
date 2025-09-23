<?php

declare(strict_types=1);

namespace M2E\M2ECloudMagentoConnector\Model;

class Integration
{
    private \Magento\Integration\Model\Integration $integration;
    private \Magento\Integration\Api\OauthServiceInterface $oauthService;
    private \Magento\Framework\Registry $registry;

    public function __construct(
        \Magento\Integration\Model\Integration $integration,
        \Magento\Integration\Api\OauthServiceInterface $oauthService,
        \Magento\Framework\Registry $registry
    ) {
        $this->integration = $integration;
        $this->oauthService = $oauthService;
        $this->registry = $registry;
    }

    public function activate(): void
    {
        if ($this->integration->getStatus() !== \Magento\Integration\Model\Integration::STATUS_ACTIVE) {
            if ($this->oauthService->createAccessToken($this->integration->getConsumerId(), 0)) {
                $this->integration->setStatus(\Magento\Integration\Model\Integration::STATUS_ACTIVE)
                                  ->save();
            }
        }
    }

    public function prepareForInstallation(): void
    {
        $this->registry->register(
            \Magento\Integration\Controller\Adminhtml\Integration::REGISTRY_KEY_CURRENT_INTEGRATION,
            $this->integration->getData()
        );
    }

    public function getConsumerKey(): string
    {
        /** @psalm-suppress UndefinedMagicMethod */
        return $this->integration->getConsumerKey();
    }

    public function getConsumerSecret(): string
    {
        /** @psalm-suppress UndefinedMagicMethod */
        return $this->integration->getConsumerSecret();
    }

    public function getToken(): ?string
    {
        /** @psalm-suppress UndefinedMagicMethod */
        return $this->integration->getToken();
    }

    public function getTokenSecret(): ?string
    {
        /** @psalm-suppress UndefinedMagicMethod */
        return $this->integration->getTokenSecret();
    }
}
