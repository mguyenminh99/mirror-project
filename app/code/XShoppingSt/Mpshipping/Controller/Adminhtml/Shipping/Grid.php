<?php
namespace XShoppingSt\Mpshipping\Controller\Adminhtml\Shipping;

class Grid extends \XShoppingSt\Mpshipping\Controller\Adminhtml\Shipping
{
    /**
     * @var \Magento\Framework\View\Result\LayoutFactory
     */
    protected $_resultLayoutFactory;

    /**
     * @param \Magento\Backend\App\Action\Context                      $context
     * @param \Magento\Framework\View\Result\LayoutFactory             $resultLayoutFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory
    ) {
        parent::__construct($context);
        $this->_resultLayoutFactory = $resultLayoutFactory;
    }
    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $resultLayout = $this->_resultLayoutFactory->create();
        $resultLayout->getLayout()->getBlock('mpshipping.shipping.edit.tab.shipping');
        return $resultLayout;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('XShoppingSt_Mpshipping::mpshipping');
    }
}
