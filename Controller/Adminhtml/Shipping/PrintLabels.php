<?php

namespace DPD\Shipping\Controller\Adminhtml\Shipping;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory as OrderCollectionFactory;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Sales\Model\Order\ShipmentFactory;
use Magento\Sales\Model\Order\ShipmentRepository;
use Magento\Framework\View\Result\PageFactory;
use DPD\Shipping\Model\ShipmentLabelsFactory;
use DPD\Shipping\Helper\Services\DPDPredictService;
use Magento\Framework\App\Response\Http\FileFactory;

class PrintLabels extends \Magento\Backend\App\Action
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
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var DPDPredictService
     */
    protected $predictService;

    /**
     * @var FileFactory
     */
    protected $fileFactory;

    /**
     * @var \DPD\Shipping\Model\ShipmentLabelsFactory
     */
    private $shipmentLabelsFactory;

    /**
     * @var ShipmentFactory
     */
    private $shipmentFactory;

    /**
     * @var ShipmentRepository
     */
    private $shipmentRepository;

    /**
     * @param Context $context
     * @param Filter $filter
     * @param OrderCollectionFactory $collectionFactory
     * @param \DPD\Shipping\Helper\Data $dataHelper
     * @param ShipmentLabelsFactory $shipmentLabelsFactory
     * @param PageFactory $pageFactory
     * @param ShipmentFactory $shipmentFactory
     * @param ShipmentRepository $shipmentRepository
     * @param DPDPredictService $predictService
     * @param FileFactory $fileFactory
     */
    public function __construct(
        Context $context,
        Filter $filter,
        OrderCollectionFactory $collectionFactory,
        \DPD\Shipping\Helper\Data $dataHelper,
        ShipmentLabelsFactory $shipmentLabelsFactory,
        PageFactory $pageFactory,
        ShipmentFactory $shipmentFactory,
        ShipmentRepository $shipmentRepository,
        DPDPredictService $predictService,
        FileFactory $fileFactory
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->dataHelper = $dataHelper;
        $this->resultPageFactory = $pageFactory;
        $this->shipmentLabelsFactory = $shipmentLabelsFactory;
        $this->predictService = $predictService;
        $this->shipmentFactory = $shipmentFactory;
        $this->shipmentRepository = $shipmentRepository;
        $this->fileFactory = $fileFactory;

        parent::__construct($context);
    }

    public function execute()
    {
        $shipment_id = $this->getRequest()->getParam("current_shipment");
        $parcels = $this->getRequest()->getParam("parcels");

        if (!$shipment_id || !$parcels) {
            $this->messageManager->addErrorMessage(__("Invalid method"));
            return $this->_redirect($this->_redirect->getRefererUrl());
        }

        try {
            $shipment = $this->shipmentRepository->get($shipment_id);
            $order = $shipment->getOrder();

            $label = $this->dataHelper->createShipment($order, $shipment, $parcels);

            $labelPDFs = [];
            $labelPDFs = array_merge($labelPDFs, $label);

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
}
