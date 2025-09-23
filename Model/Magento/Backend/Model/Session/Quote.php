<?php

namespace M2E\M2ECloudMagentoConnector\Model\Magento\Backend\Model\Session;

class Quote extends \Magento\Backend\Model\Session\Quote
{
    public function clearStorage()
    {
        parent::clearStorage();
        $this->_quote = null;

        return $this;
    }
}
