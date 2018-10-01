<?php

namespace DPD\Shipping\Config\Source\Settings;

use Magento\Framework\Option\ArrayInterface;

class DaysInWeek implements ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        $options = array(
            array(
                'value' => '1',
                'label' => __('Monday'),
            ),
            array(
                'value' => '2',
                'label' => __('Tuesday'),
            ),
            array(
                'value' => '3',
                'label' => __('Wednesday'),
            ),
            array(
                'value' => '4',
                'label' => __('Thursday'),
            ),
            array(
                'value' => '5',
                'label' => __('Friday'),
            ),
            array(
                'value' => '6',
                'label' => __('Saturday'),
            ),
            array(
                'value' => '7',
                'label' => __('Sunday'),
            ),
        );
        return $options;
    }
}
