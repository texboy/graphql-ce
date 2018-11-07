<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Catalog\Block\Adminhtml\Product\Edit\Action\Attribute\Tab;

use Magento\Customer\Api\Data\GroupInterface;

/**
 * Products mass update inventory tab
 *
 * @api
 * @since 100.0.2
 */
class Inventory extends \Magento\Backend\Block\Widget implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * @var \Magento\CatalogInventory\Model\Source\Backorders
     */
    protected $_backorders;

    /**
     * @var \Magento\CatalogInventory\Api\StockConfigurationInterface
     */
    protected $stockConfiguration;

    /**
     * @var array
     * @since 101.0.0
     */
    protected $disabledFields = [];

    /**
     * @var \Magento\Framework\Serialize\SerializerInterface
     */
    private $serializer;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\CatalogInventory\Model\Source\Backorders $backorders
     * @param \Magento\CatalogInventory\Api\StockConfigurationInterface $stockConfiguration
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\CatalogInventory\Model\Source\Backorders $backorders,
        \Magento\CatalogInventory\Api\StockConfigurationInterface $stockConfiguration,
        array $data = [],
        \Magento\Framework\Serialize\SerializerInterface $serializer = null
    ) {
        $this->_backorders = $backorders;
        $this->stockConfiguration = $stockConfiguration;
        $this->serializer = $serializer ?? \Magento\Framework\App\ObjectManager::getInstance()
            ->get(\Magento\Framework\Serialize\SerializerInterface::class);
        parent::__construct($context, $data);
    }

    /**
     * Retrieve Backorders Options
     *
     * @return array
     */
    public function getBackordersOption()
    {
        return $this->_backorders->toOptionArray();
    }

    /**
     * Retrieve field suffix
     *
     * @return string
     */
    public function getFieldSuffix()
    {
        return 'inventory';
    }

    /**
     * Retrieve current store id
     *
     * @return int
     */
    public function getStoreId()
    {
        $storeId = $this->getRequest()->getParam('store');
        return (int) $storeId;
    }

    /**
     * Get default config value
     *
     * @param string $field
     * @return string|null
     */
    public function getDefaultConfigValue($field)
    {
        return $this->stockConfiguration->getDefaultConfigValue($field);
    }

    /**
     * Returns min_sale_qty configuration for the ALL Customer Group
     * @return int
     */
    public function getDefaultMinSaleQty()
    {
        $default = $this->stockConfiguration->getDefaultConfigValue('min_sale_qty');
        if (!is_numeric($default)) {
            $default = $this->serializer->unserialize($default);
            $default = isset($default[GroupInterface::CUST_GROUP_ALL]) ? $default[GroupInterface::CUST_GROUP_ALL] : 1;
        }

        return (int) $default;
    }

    /**
     * Tab settings
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Advanced Inventory');
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Advanced Inventory');
    }

    /**
     * @return bool
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * @param string $fieldName
     * @return bool
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @since 101.0.0
     */
    public function isAvailable($fieldName)
    {
        return true;
    }
}
