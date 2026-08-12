<?php

declare(strict_types=1);

namespace M2E\M2ECloudMagentoConnector\Model\Magento\Quote;

class CustomerFinder
{
    private \Magento\Customer\Model\CustomerFactory $customerFactory;

    public function __construct(
        \Magento\Customer\Model\CustomerFactory $customerFactory
    ) {
        $this->customerFactory = $customerFactory;
    }

    public function findByEmail(string $email, int $websiteId): ?\Magento\Customer\Api\Data\CustomerInterface
    {
        /** @var \Magento\Customer\Model\Customer $customerObject */
        $customerObject = $this->customerFactory->create();
        $customerObject->setWebsiteId($websiteId);
        $customerObject->loadByEmail($email);

        if ($customerObject->getId() !== null) {
            return $customerObject->getDataModel();
        }

        return null;
    }
}
