<?php

namespace DPD\Shipping\Model\ResourceModel;

use \Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class ShipmentLabels extends AbstractDb
{
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context
    ) {
        parent::__construct($context);
    }

    protected function _construct()
    {
        $this->_init('dpd_shipment_label', 'id_dpdcarrier_label');
    }
}
