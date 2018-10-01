<?php

namespace DPD\Shipping\Model\ResourceModel\ShipmentLabels;

use \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'id_dpdcarrier_label';
    protected $_eventPrefix = 'dpd_shipping_shipment_collection';
    protected $_eventObject = 'shipment_collection';

    /**
     * Define resource model and item
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            'DPD\Shipping\Model\ShipmentLabels',
            'DPD\Shipping\Model\ResourceModel\ShipmentLabels'
        );
    }
}
