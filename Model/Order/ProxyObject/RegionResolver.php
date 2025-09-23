<?php

declare(strict_types=1);

namespace M2E\M2ECloudMagentoConnector\Model\Order\ProxyObject;

class RegionResolver
{
    private \Magento\Directory\Model\CountryFactory $countryFactory;
    private \Magento\Directory\Helper\Data $directoryHelper;

    public function __construct(
        \Magento\Directory\Model\CountryFactory $countryFactory,
        \Magento\Directory\Helper\Data $directoryHelper
    ) {
        $this->countryFactory = $countryFactory;
        $this->directoryHelper = $directoryHelper;
    }

    public function getRegionIdByName(string $countryId, string $regionName): ?string
    {
        $country = $this->getCountry($countryId);
        if (!$country->getId()) {
            throw new \Exception((string)__('Country not found.'));
        }

        $countryRegions = $country->getRegionCollection();
        $countryRegions->getSelect()->where('code = ? OR default_name = ?', $regionName);
        /** @var \Magento\Directory\Model\Region $region */
        $region = $countryRegions->getFirstItem();

        $isRegionRequired = $this->directoryHelper->isRegionRequired($country->getId());
        if ($isRegionRequired && !$region->getId()) {
            throw new \Exception(
                (string)__('Invalid Region/State value: %region_name', ['region_name' => $regionName])
            );
        }

        return $region->getRegionId();
    }

    private function getCountry($countryId): \Magento\Directory\Model\Country
    {
        $country = $this->countryFactory->create();
        try {
            $country->loadByCode($countryId);
        } catch (\Exception $e) {
        }

        return $country;
    }
}
