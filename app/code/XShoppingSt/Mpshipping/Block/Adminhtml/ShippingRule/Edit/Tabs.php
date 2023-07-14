<?php
namespace XShoppingSt\Mpshipping\Block\Adminhtml\ShippingRule\Edit;

/**
 * class Tabs for creating the tabs in shipping section
 */
class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * Class constructor
     *
     * @return void
     */
    public function _construct()
    {
        parent::_construct();
        $this->setId('page_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Shipping Rule Information'));
    }

    /**
     * @return $this
     */
    public function _beforeToHtml()
    {
        $this->addTab(
            'main_section',
            [
                'label' => __('Shipping Rule Info'),
                'title' => __('Shipping Rule Info'),
                'content' => $this->getLayout()->createBlock(
                    \XShoppingSt\Mpshipping\Block\Adminhtml\ShippingRule\Edit\Tab\Main::class
                )->toHtml(),
                'active' => true
            ]
        );
        return parent::_beforeToHtml();
    }
}
