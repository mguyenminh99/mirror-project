<?php
namespace XShoppingSt\Mpshipping\Block\Adminhtml\Shippingset;

/**
 * User edit page
 */
class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    private $coreRegistry = null;

    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {

        $this->coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * Class constructor
     *
     * @return void
     */
    public function _construct()
    {
        $this->_objectId = 'entity_id';
        $this->_controller = 'adminhtml_shippingset';
        $this->_blockGroup = 'XShoppingSt_Mpshipping';

        parent::_construct();

        $this->buttonList->update('save', 'label', __('Save Shipping'));
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {
        if ($this->coreRegistry->registry('mpshippingset_shipping')->getId()) {
            return __("Edit Shipping Set");
        } else {
            return __('New Shipping Set');
        }
    }
}
