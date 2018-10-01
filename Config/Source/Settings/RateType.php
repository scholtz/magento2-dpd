<?php

namespace DPD\Shipping\Config\Source\Settings;

use Magento\Framework\Option\ArrayInterface;

class RateType implements ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        $options = array(
            array(
                'value' => 'flat',
                'label' => __('Flat'),
            ),
            array(
                'value' => 'table',
                'label' => __('Table'),
            ),
        );
        return $options;
    }
}
