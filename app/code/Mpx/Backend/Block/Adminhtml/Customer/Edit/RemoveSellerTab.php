<?php

namespace Mpx\Backend\Block\Adminhtml\Customer\Edit;

/**
 * Customer account form block.
 */
class RemoveSellerTab extends \XShoppingSt\Marketplace\Block\Adminhtml\Customer\Edit\RemoveSellerTab
{
    /***
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @var string
     */
    protected $_isSeller;

    /**
     * @param \Magento\Backend\Block\Template\Context           $context
     * @param \Magento\Framework\Registry                       $registry
     * @param \XShoppingSt\Marketplace\Block\Adminhtml\Customer\Edit $customerEdit
     * @param array                                             $data
     */

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \XShoppingSt\Marketplace\Block\Adminhtml\Customer\Edit $customerEdit,
        array $data = []
    ) {
        $this->_isSeller = 0;
        $this->_coreRegistry = $registry;
        $this->customerEdit = $customerEdit;
        parent::__construct($context, $registry, $formFactory, $customerEdit, $data);
    }

    /**
     * @return string
     */
    protected function _toHtml()
    {
        if ($this->canShowTab()) {
            $this->initForm();

            return parent::_toHtml() . $this->getChildHtml('seller_edit_removeseller_show_modal_tab_view');
        } else {
            return '';
        }
    }
}
