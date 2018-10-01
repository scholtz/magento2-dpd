<?php


namespace DPD\Shipping\Block\Adminhtml\Shipment;

use Magento\Sales\Api\ShipmentRepositoryInterface;

class MenuItem
{
    private $shipmentRepository;

    public function __construct(ShipmentRepositoryInterface $shipmentRepository)
    {
        $this->shipmentRepository = $shipmentRepository;
    }

    public function beforePushButtons(
        \Magento\Backend\Block\Widget\Button\Toolbar\Interceptor $subject,
        \Magento\Framework\View\Element\AbstractBlock $context,
        \Magento\Backend\Block\Widget\Button\ButtonList $buttonList
    ) {
        if (!$context instanceof \Magento\Shipping\Block\Adminhtml\View\Interceptor) {
            if (!$context instanceof \Magento\Shipping\Block\Adminhtml\View) {
                return [$context, $buttonList];
            }
        }
        $this->_request = $context->getRequest();

        if ($this->_request->getFullActionName() == 'adminhtml_order_shipment_view') {
            $buttonList->add(
                'dpd_generate_label',
                ['label' => __('DPD - Create label(s)'), 'class' => 'reset'],
                -1
            );
        }
    }
}
