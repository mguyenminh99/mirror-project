<?php
namespace XShoppingSt\MpAssignProduct\Block\Adminhtml\Product;

class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    /**
     * Initialize MpAssignProduct MpAssignProduct Edit Block
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'id';
        $this->_blockGroup = 'XShoppingSt_MpAssignProduct';
        $this->_controller = 'adminhtml_product';
        parent::_construct();
        $this->buttonList->update('save', 'label', __('Update Status'));
        $this->buttonList->remove('delete');
        $this->buttonList->remove('reset');
    }

    /**
     * Retrieve text for header element depending on loaded image
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {
        return __('Assigned Product');
    }

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }
}
