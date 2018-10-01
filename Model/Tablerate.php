<?php

namespace DPD\Shipping\Model;

class Tablerate extends \Magento\OfflineShipping\Model\Carrier\Tablerate
{
    protected function _construct()
    {
        $this->_init('dpd_shipping_tablerate', 'entity_id');
    }
}
