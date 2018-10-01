<?php

namespace DPD\Shipping\Controller\Adminhtml\Order;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory as OrderCollectionFactory;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Sales\Model\Order;
use DPD\Shipping\Model\ShipmentLabelsFactory;

class CreateShipment extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Ui\Component\MassAction\Filter
     */
    protected $filter;

    /**
     * @var object
     */
    protected $collectionFactory;

    /**
     * @var \DPD\Shipping\Helper\Data
     */
    protected $dataHelper;

    /**
     * @var FileFactory
     */
    protected $fileFactory;

    /**
     * @var \DPD\Shipping\Model\ShipmentLabelsFactory
     */
    private $shipmentLabelsFactory;

    /**
     * @param Context $context
     * @param Filter $filter
     * @param OrderCollectionFactory $collectionFactory
     * @param \DPD\Shipping\Helper\Data $dataHelper
     * @param ShipmentLabelsFactory $shipmentLabelsFactory
     * @param FileFactory $fileFactory
     */
    public function __construct(
        Context $context,
        Filter $filter,
        OrderCollectionFactory $collectionFactory,
        \DPD\Shipping\Helper\Data $dataHelper,
        ShipmentLabelsFactory $shipmentLabelsFactory,
        FileFactory $fileFactory
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->dataHelper = $dataHelper;
        $this->fileFactory = $fileFactory;
        $this->shipmentLabelsFactory = $shipmentLabelsFactory;
        parent::__construct($context);
    }

    /**
     * Execute action
     *
     * @return \Magento\Framework\Controller\Result\|\Magento\Framework\App\ResponseInterface
     * @throws \Magento\Framework\Exception\LocalizedException|\Exception
     */
    public function execute()
    {
        try {
            $collection = $this->collectionFactory->create();
            $collection = $this->filter->getCollection($collection);

            $labelPDFs = array();

            foreach ($collection as $order) {
                if ($this->dataHelper->isDPDOrder($order)) {
                    if ($order->getShipmentsCollection()->count() > 1) {
                        $this->messageManager->addErrorMessage(
                            sprintf(__('Order %s has more than 1 shipment, go the the shipment overview to generate a label.'), $order->getIncrementId())
                        );
                        continue;
                    }

                    $label = $this->dataHelper->createShipment($order);

                    $labelPDFs = array_merge($labelPDFs, $label);
                }
            }


            if (count($labelPDFs) == 0) {
                $this->messageManager->addErrorMessage(
                    __('DPD - There are no shipping labels generated.')
                );

                return $this->_redirect($this->_redirect->getRefererUrl());
            }

            $resultPDF = $this->dataHelper->combinePDFFiles($labelPDFs);

            return $this->fileFactory->create(
                'DPD-shippinglabels.pdf',
                $resultPDF,
                DirectoryList::VAR_DIR,
                'application/pdf'
            );
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            return $this->_redirect($this->_redirect->getRefererUrl());
        }
    }

    /**
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    private function redirect()
    {
        $redirectPath = 'sales/order/index';

        $resultRedirect = $this->resultRedirectFactory->create();

        $resultRedirect->setPath($redirectPath);

        return $resultRedirect;
    }
}
