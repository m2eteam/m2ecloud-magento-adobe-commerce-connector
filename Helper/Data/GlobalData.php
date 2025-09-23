<?php

namespace M2E\M2ECloudMagentoConnector\Helper\Data;

class GlobalData
{
    private \Magento\Framework\Registry $registryModel;

    public function __construct(
        \Magento\Framework\Registry $registryModel
    ) {
        $this->registryModel = $registryModel;
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getValue($key)
    {
        $globalKey = \M2E\M2ECloudMagentoConnector\Helper\Module::IDENTIFIER . '_' . $key;

        return $this->registryModel->registry($globalKey);
    }

    /**
     * @param string $key
     * @param mixed $value
     *
     * @return void
     */
    public function setValue($key, $value): void
    {
        $globalKey = \M2E\M2ECloudMagentoConnector\Helper\Module::IDENTIFIER . '_' . $key;
        $this->registryModel->register($globalKey, $value);
    }

    /**
     * @param string $key
     *
     * @return void
     */
    public function unsetValue($key): void
    {
        $globalKey = \M2E\M2ECloudMagentoConnector\Helper\Module::IDENTIFIER . '_' . $key;
        $this->registryModel->unregister($globalKey);
    }
}
