<?php

namespace DPDBenelux\Shipping\Model;

use Magento\Framework\UrlInterface;

class CheckoutConfigProvider implements \Magento\Checkout\Model\ConfigProviderInterface
{
    const DPD_GOOGLE_MAPS_WIDTH = 'carriers/dpdpickup/map_width';
    const DPD_GOOGLE_MAPS_HEIGHT = 'carriers/dpdpickup/map_height';

    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    private $scopeConfig;

    public function __construct(
        UrlInterface $urlBuilder,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->scopeConfig = $scopeConfig;
    }

    public function getConfig()
    {
        $output['dpd_parcelshop_url'] = $this->urlBuilder->getUrl('dpd/parcelshops', ['_secure' => true]);
        $output['dpd_parcelshop_save_url'] = $this->urlBuilder->getUrl('dpd/parcelshops/save', ['_secure' => true]);
        $output['dpd_googlemaps_width'] = $this->scopeConfig->getValue(self::DPD_GOOGLE_MAPS_WIDTH);
        $output['dpd_googlemaps_height'] = $this->scopeConfig->getValue(self::DPD_GOOGLE_MAPS_HEIGHT);
        return $output;
    }
}
