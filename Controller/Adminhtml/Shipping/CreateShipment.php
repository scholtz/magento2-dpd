<?php

namespace DPD\Shipping\Controller\Adminhtml\Shipping;

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
use Magento\Sales\Model\ResourceModel\Order\Shipment\CollectionFactory;
use DPD\Shipping\Model\ShipmentLabelsFactory;

class CreateShipment extends \Magento\Sales\Controller\Adminhtml\Order\AbstractMassAction
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
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param \DPD\Shipping\Helper\Data $dataHelper
     * @param FileFactory $fileFactory
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectioNFactory $collectionFactory,
        \DPD\Shipping\Helper\Data $dataHelper,
        FileFactory $fileFactory
    ) {
        $this->filter = $filter;
        $this->dataHelper = $dataHelper;
        $this->fileFactory = $fileFactory;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context, $filter);
    }

    public function massAction(AbstractCollection $collection)
    {
        try {
            $labelPDFs = array();

            if ($collection->getSize()) {
                /** @var \Magento\Sales\Model\Order\Shipment $shipment */
                foreach ($collection as $shipment) {
                    $order = $shipment->getOrder();
                    if ($this->dataHelper->isDPDOrder($order)) {
                        $label = $this->dataHelper->createShipment($order, $shipment);

                        $labelPDFs = array_merge($labelPDFs, $label);
                    }
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
