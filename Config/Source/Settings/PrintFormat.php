<?php

namespace DPD\Shipping\Config\Source\Settings;

use Magento\Framework\Option\ArrayInterface;

class PrintFormat implements ArrayInterface
{
    /**
     * Return mode option array
     * @return array
     */
    public function toOptionArray()
    {
		// @codingStandardsIgnoreStart
		$options = [
			['value' => 'A4', 'label' => __('A4')],
			['value' => 'A6', 'label' => __('A6')],
		];
		// @codingStandardsIgnoreEnd
        return $options;
    }
}
