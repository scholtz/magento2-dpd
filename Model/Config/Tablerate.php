<?php

namespace DPD\Shipping\Model\Config;

use Magento\Framework\Model\AbstractModel;

/**
 * Backend model for shipping table rates CSV importing
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Tablerate extends \Magento\Framework\App\Config\Value
{
    /**
     * @var \DPD\Shipping\Model\ResourceModel\Carrier\TablerateFactory
     */
    protected $_tablerateFactory;
    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $config
     * @param \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList
     * @param \DPD\Shipping\Model\ResourceModel\TablerateFactory $tablerateFactory
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Config\ScopeConfigInterface $config,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \DPD\Shipping\Model\ResourceModel\TablerateFactory $tablerateFactory,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->_tablerateFactory = $tablerateFactory;
        parent::__construct($context, $registry, $config, $cacheTypeList, $resource, $resourceCollection, $data);
    }
    /**
     * @return $this
     */
    public function afterSave()
    {
        if ($this->_registry->registry('dpd_import_processed')) {
            return parent::afterSave();
        }

        $this->_registry->register('dpd_import_processed', true);
        /** @var \DPD\Shipping\Model\ResourceModel\Tablerate $tableRate */
        $tableRate = $this->_tablerateFactory->create();
        $tableRate->uploadAndImport($this);
        return parent::afterSave();
    }
}
