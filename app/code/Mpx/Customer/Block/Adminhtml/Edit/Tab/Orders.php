<?php

namespace Mpx\Customer\Block\Adminhtml\Edit\Tab;

use Magento\Customer\Controller\RegistryConstants;

/**
 * Adminhtml customer orders grid block
 *
 * @api
 * @since 100.0.2
 */
class Orders extends \Magento\Customer\Block\Adminhtml\Edit\Tab\Orders
{
    /**
     * Apply various selection filters to prepare the sales order grid collection.
     *
     * @return \Magento\Backend\Block\Widget\Grid\Extended
     */
    protected function _prepareCollection()
    {
        $collection = $this->_collectionFactory->getReport('sales_order_grid_data_source');
        $collection->getSelect()->joinLeft(
            'sales_order as so',
            'main_table.customer_id = so.customer_id',
            ['firstname' => 'so.customer_firstname', 'lastname' => 'so.customer_lastname']
        );
        $collection->getSelect()->joinLeft(
            'marketplace_saleslist as ml',
            'main_table.entity_id = ml.order_id',
            ['ml.seller_id']
        );
        $collection->getSelect()->joinLeft(
            'marketplace_userdata as mu',
            'ml.seller_id = mu.seller_id AND mu.store_id=1',
            ['shop_title' => 'mu.shop_title']
        );
        $collection->addFieldToSelect(
            'entity_id'
        )->addFieldToSelect(
            'increment_id'
        )->addFieldToSelect(
            'customer_id'
        )->addFieldToSelect(
            'created_at'
        )->addFieldToSelect(
            'grand_total'
        )->addFieldToSelect(
            'order_currency_code'
        )->addFieldToSelect(
            'store_id'
        )->addFieldToSelect(
            'billing_name'
        )->addFieldToSelect(
            'shipping_name'
        )->addFieldToFilter(
            'customer_id',
            $this->_coreRegistry->registry(RegistryConstants::CURRENT_CUSTOMER_ID)
        );
        $collection->getSelect()->group('main_table.entity_id');
        $this->setCollection($collection);
        return \Magento\Backend\Block\Widget\Grid\Extended::_prepareCollection();
    }

    /**
     * @inheritdoc
     */
    protected function _prepareColumns()
    {
        $this->addColumn('increment_id', ['header' => __('Order #'), 'width' => '100', 'index' => 'increment_id']);

        $this->addColumn(
            'created_at',
            ['header' => __('Purchased'), 'index' => 'created_at', 'type' => 'datetime']
        );

        $this->addColumn(
            'grand_total',
            [
                'header' => __('Order Total'),
                'index' => 'grand_total',
                'type' => 'currency',
                'currency' => 'order_currency_code',
                'rate'  => 1
            ]
        );

        $this->addColumn(
            'lastname',
            [
                'header' => __('Customer Name Order'),
                'index' => 'lastname',
                'renderer' => \Mpx\Customer\Block\Adminhtml\Renderer\CustomerName::class
            ]
        );

        $this->addColumn(
            'shop_title',
            [
                'header' => __('Purchase Store'),
                'index' => 'shop_title'
            ]
        );

        $this->sortColumnsByOrder();
        return $this;
    }
}
