<?php


namespace DPD\Shipping\Block\Adminhtml\Order;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class PrintShippingList extends Template
{
    public function __construct(Context $context)
    {
        parent::__construct($context);
    }
}
