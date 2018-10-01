<?php

namespace DPD\Shipping\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Quote\Model\QuoteRepository;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\OrderRepository;

class SalesOrderSaveBefore implements ObserverInterface
{
    /**
     * @var QuoteRepository
     */
    private $quoteRepository;
    
    /**
     * @var \Magento\Framework\App\State
     */
    private $state;

    public function __construct(
        QuoteRepository $quoteRepository,
        \Magento\Framework\App\State $state
    ) {
        $this->quoteRepository = $quoteRepository;
        $this->state = $state;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        // Ignore adminhtml
        if ($this->state->getAreaCode() == \Magento\Framework\App\Area::AREA_ADMINHTML) {
            return;
        }

        $order = $observer->getEvent()->getOrder();
        /** @var Order $order */

        if ($order->getShippingMethod() != 'dpdpickup_dpdpickup') {
            return;
        }

        $quoteId = $order->getQuoteId();
        try {
            $quote = $this->quoteRepository->get($quoteId);
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            $quote = null;
        }

        // Happens when the order has already been placed in which caes this event has already
        // been called succesfully
        if ($quote == null) {
            return;
        }

        // Happens when editing old orders before 1.0.7
        if ($quote->getDpdParcelshopId() == '') {
            return;
        }

        $order->setDpdParcelshopId($quote->getDpdParcelshopId());
        $order->setDpdCompany($quote->getDpdCompany());
        $order->setDpdStreet($quote->getDpdStreet());
        $order->setDpdZipcode($quote->getDpdZipcode());
        $order->setDpdCity($quote->getDpdCity());
        $order->setDpdCountry($quote->getDpdCountry());
    }
}
