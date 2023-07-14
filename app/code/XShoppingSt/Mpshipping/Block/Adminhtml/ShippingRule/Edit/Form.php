<?php
namespace XShoppingSt\Mpshipping\Block\Adminhtml\ShippingRule\Edit;

/**
 * Adminhtml permissions warehouse edit form
 *
 */
/**
 * @SuppressWarnings(PHPMD.DepthOfInheritance)
 */
class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    /**
     * @return $this
     */
    public function _prepareForm()
    {
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            [
                'data' => [
                            'id' => 'edit_form',
                            'action' => $this->getData('action'),
                            'method' => 'post']
                        ]
        );
        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
