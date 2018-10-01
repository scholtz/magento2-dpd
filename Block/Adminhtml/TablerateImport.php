<?php

namespace DPD\Shipping\Block\Adminhtml;

class TablerateImport extends \Magento\Framework\Data\Form\Element\AbstractElement
{
    protected $shipconfig;

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setType('file');
    }
    /**
     * Enter description here...
     *
     * @return string
     */
    public function getElementHtml()
    {

        // get the group name
        $groupId = $this->getContainer()['group']['id'];

        $html = '';
        $html .= '<input id="time_condition_'.$groupId.'" type="hidden" name="' . $this->getName() . '" value="' . time() . '" />';
        $html .= <<<EndHTML
        <script>
        
        var groupId = '$groupId';
        
        require(['prototype'], function(){
        Event.observe($('carriers_' + groupId + '_condition_name'), 'change', checkConditionName.bind(this));
        function checkConditionName(event)
        {
            var conditionNameElement = Event.element(event);
            if (conditionNameElement && conditionNameElement.id) {
                $('time_condition_' + groupId).value = '_' + conditionNameElement.value + '/' + Math.random();
            }
        }
        });
        </script>
EndHTML;
        $html .= parent::getElementHtml();
        return $html;
    }
}
