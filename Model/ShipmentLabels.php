<?php

namespace DPD\Shipping\Model;

use Magento\Framework\Model\AbstractModel;

class ShipmentLabels extends AbstractModel
{
    protected function _construct()
    {
        $this->_init('DPD\Shipping\Model\ResourceModel\ShipmentLabels');
    }
}
