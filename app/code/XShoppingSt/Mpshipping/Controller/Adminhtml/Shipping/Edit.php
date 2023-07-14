<?php
namespace XShoppingSt\Mpshipping\Controller\Adminhtml\Shipping;

use Magento\Framework\Controller\ResultFactory;

use Magento\Framework\Locale\Resolver;

class Edit extends \XShoppingSt\Mpshipping\Controller\Adminhtml\Shippingset
{
    /**
     * @var \Magento\Framework\Registry
     */
    private $coreRegistry;

    /**
     * @var \XShoppingSt\Mpshipping\Model\MpshippingFactory
     */
    private $mpshippingFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry         $coreRegistry
     * @param CollectionFactory                   $mpshippingFactory
     * @param RoleFac                             $salesOrderCollectionFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \XShoppingSt\Mpshipping\Model\MpshippingFactory $mpshippingFactory
    ) {
        parent::__construct($context);
        $this->coreRegistry = $coreRegistry;
        $this->mpshippingFactory = $mpshippingFactory;
    }

    /**
     * @return void
     */
    public function execute()
    {
        $id=(int)$this->getRequest()->getParam('id');
        $shippingModel=$this->mpshippingFactory->create();
        if ($id) {
            $shippingModel->load($id);
            if (!$shippingModel->getMpshippingId()) {
                $this->messageManager->addError(__('This Shipping rule is no longer exists.'));
                $this->_redirect('mpshipping/*/');
                return;
            }
        }
        $this->coreRegistry->register('mpshippingrule_shipping', $shippingModel);
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('XShoppingSt_Mpshipping::mpshipping');
        $resultPage->getConfig()->getTitle()->prepend(__('Shipping Rule'));
        $resultPage->addContent(
            $resultPage
            ->getLayout()
            ->createBlock(
                \XShoppingSt\Mpshipping\Block\Adminhtml\ShippingRule\Edit::class
            )
        );
        $resultPage->addLeft(
            $resultPage
            ->getLayout()
            ->createBlock(
                \XShoppingSt\Mpshipping\Block\Adminhtml\ShippingRule\Edit\Tabs::class
            )
        );
          return $resultPage;
    }

    /**
     * check permission
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('XShoppingSt_Mpshipping::mpshipping');
    }
}
