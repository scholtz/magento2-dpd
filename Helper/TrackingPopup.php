<?php

namespace DPD\Shipping\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\AbstractHelper;

class TrackingPopup extends AbstractHelper
{
    public function __construct(\Magento\Framework\App\Helper\Context $context)
    {
        parent::__construct($context);
    }

    public function getCarrierTitle()
    {
        $carrierCode = 'dpdpredict';

        $carrierTitle = $this->scopeConfig->getValue(
            'carriers/' . $carrierCode  . '/title',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        return $carrierTitle;
    }

    public function getErrorMessage()
    {
        return __('Tracking not available');
    }

    public function getProgressdetail()
    {
        return '';
    }

    public function getTracking()
    {
        return '';
    }
}
