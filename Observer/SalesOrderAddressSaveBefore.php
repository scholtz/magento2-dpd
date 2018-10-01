<?php

namespace DPD\Shipping\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Address;
use Magento\Sales\Model\OrderRepository;

class SalesOrderAddressSaveBefore implements ObserverInterface
{
    /**
     * @var \Magento\Framework\App\State
     */
    private $state;

    public function __construct(\Magento\Framework\App\State $state)
    {
        $this->state = $state;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        // Ignore adminhtml
        if ($this->state->getAreaCode() == \Magento\Framework\App\Area::AREA_ADMINHTML) {
            return;
        }

        $shippingAddress = $observer->getEvent()->getAddress();
        /** @var Address $shippingAddress */

        $order = $shippingAddress->getOrder();
        /** @var Order $order */

        // Ignore all orders that aren't dpd pickup
        if ($order->getShippingMethod() != 'dpdpickup_dpdpickup') {
            return;
        }

        // If the address isn't the shipping address
        if ($shippingAddress->getAddressType() != 'shipping') {
            return;
        }

        $shippingAddress->setFirstname('DPD ParcelShop: ');
        $shippingAddress->setLastname($order->getDpdCompany());
        $shippingAddress->setStreet($order->getDpdStreet());
        $shippingAddress->setCity($order->getDpdCity());
        $shippingAddress->setPostcode($order->getDpdZipcode());
        $shippingAddress->setCountryId($order->getDpdCountry());

        // empty this otherwise you'd get customer data and DPD parcelshop data mixed up
        $shippingAddress->setCompany('');
        $shippingAddress->setTelephone('');
    }
}
