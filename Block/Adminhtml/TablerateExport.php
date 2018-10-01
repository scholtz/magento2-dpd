<?php

namespace DPD\Shipping\Block\Adminhtml;

class TablerateExport extends \Magento\Framework\Data\Form\Element\AbstractElement
{
    /**
     * @var \Magento\Backend\Model\UrlInterface
     */
    protected $_backendUrl;
    /**
     * @param \Magento\Framework\Data\Form\Element\Factory $factoryElement
     * @param \Magento\Framework\Data\Form\Element\CollectionFactory $factoryCollection
     * @param \Magento\Framework\Escaper $escaper
     * @param \Magento\Backend\Helper\Data $helper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Data\Form\Element\Factory $factoryElement,
        \Magento\Framework\Data\Form\Element\CollectionFactory $factoryCollection,
        \Magento\Framework\Escaper $escaper,
        \Magento\Backend\Model\UrlInterface $backendUrl,
        array $data = []
    ) {
        parent::__construct($factoryElement, $factoryCollection, $escaper, $data);
        $this->_backendUrl = $backendUrl;
    }
    /**
     * @return string
     */
    public function getElementHtml()
    {
        /** @var \Magento\Backend\Block\Widget\Button $buttonBlock  */
        $buttonBlock = $this->getForm()->getParent()->getLayout()->createBlock(
            \Magento\Backend\Block\Widget\Button::class
        );

        // get the group name
        $groupId = $this->getContainer()['group']['id'];

        $params = [];
        $params['website'] = $buttonBlock->getRequest()->getParam('website');
        $params['shipping_method'] = $groupId;
        $url = $this->_backendUrl->getUrl("dpdshipping/tablerate/export", $params);
        $data = [
            'label' => __('Export CSV'),
            'onclick' => "setLocation('" .
            $url .
            "conditionName/' + $('carriers_" . $groupId . "_condition_name').value + '/tablerates.csv' )",
            'class' => '',
        ];
        $html = $buttonBlock->setData($data)->toHtml();
        return $html;
    }
}
