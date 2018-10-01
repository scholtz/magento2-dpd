<?php

namespace DPD\Shipping\Block\Adminhtml\Shipment;

class PrintLabelModal extends \Magento\Backend\Block\Template
{

    public function __construct(\Magento\Backend\Block\Template\Context $context, array $data = [])
    {
        parent::__construct($context, $data);
    }

    public function getPostUrl()
    {
        return $this->_urlBuilder->getUrl("dpdshipping/shipping/printlabels");
    }

    public function getShipmentId()
    {
        return $this->getRequest()->getParam("shipment_id");
    }
}
