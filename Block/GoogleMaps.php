<?php

namespace DPD\Shipping\Block;

class GoogleMaps extends \Magento\Framework\View\Element\Template
{
    const DPD_GOOGLE_MAPS_API = 'carriers/dpdpickup/google_maps_api';

    public function __construct(\Magento\Framework\View\Element\Template\Context $context)
    {
        parent::__construct($context);
    }

    public function getApiKey()
    {
        return $this->_scopeConfig->getValue(self::DPD_GOOGLE_MAPS_API);
    }
}
