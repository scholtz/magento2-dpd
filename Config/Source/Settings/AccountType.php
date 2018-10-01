<?php

namespace DPD\Shipping\Config\Source\Settings;

use Magento\Framework\Option\ArrayInterface;

class AccountType implements ArrayInterface
{
    /**
     * Return mode option array
     * @return array
     */
    public function toOptionArray()
    {
		// @codingStandardsIgnoreStart
		$options = [
			['value' => 'B2C', 'label' => __('B2C')],
			['value' => 'B2B', 'label' => __('B2B')],
		];
		// @codingStandardsIgnoreEnd
        return $options;
    }
}
