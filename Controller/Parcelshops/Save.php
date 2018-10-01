<?php

namespace DPD\Shipping\Controller\Parcelshops;

use DPD\Shipping\Helper\Data;
use Magento\Framework\View\Asset\Repository;

class Save extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \DPD\Shipping\Helper\Data
     */
    protected $data;

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultPageFactory;

    /**
     * @var \Magento\Framework\View\Asset\Repository
     */
    protected $assetRepo;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;
    /**
     * @var \Magento\Quote\Api\CartRepositoryInterface
     */
    protected $quoteRepository;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        Data $data,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        Repository $assetRepo,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Quote\Api\CartRepositoryInterface $quoteRepository
    ) {
        parent::__construct($context);

        $this->data = $data;
        $this->resultPageFactory = $resultPageFactory;
        $this->assetRepo = $assetRepo;
        $this->checkoutSession = $checkoutSession;
        $this->quoteRepository = $quoteRepository;
    }

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $parcelData = $this->getRequest()->getPostValue();

        $resultPage = $this->resultPageFactory->create();

        $quote = $this->checkoutSession->getQuote();

        $quote
        ->setDpdParcelshopId($parcelData['parcelShopId'])
        ->setDpdCompany($parcelData['company'])
        ->setDpdStreet($parcelData['houseno'])
        ->setDpdZipcode($parcelData['zipcode'])
        ->setDpdCity($parcelData['city'])
        ->setDpdCountry($parcelData['country'])
        ->setDpdExtraInfo($parcelData['extra_info']);

        $this->quoteRepository->save($quote);

        $block = $resultPage->getLayout()
            ->createBlock('DPD\Shipping\Block\ParcelshopInfo')
            ->setTemplate('DPD_Shipping::checkout/shipping/parcelshop-info.phtml');
        $block->setParcelshop($parcelData);
        $block->setQuote($quote);
        $blockHtml = $block->toHtml();
        $this->getResponse()->setBody($blockHtml);
    }
}
