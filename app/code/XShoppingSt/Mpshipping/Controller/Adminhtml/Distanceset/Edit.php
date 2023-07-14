<?php
namespace XShoppingSt\Mpshipping\Controller\Adminhtml\Distanceset;

use Magento\Framework\Controller\ResultFactory;

use Magento\Framework\Locale\Resolver;

class Edit extends \XShoppingSt\Mpshipping\Controller\Adminhtml\Distanceset
{
    /**
     * @var \Magento\Framework\Registry
     */
    private $coreRegistry;

    /**
     * @var \XShoppingSt\Mpshipping\Model\MpshippingDistFactory
     */
    private $mpshippingDistFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry         $coreRegistry
     * @param CollectionFactory                   $mpshippingDistFactory
     * @param RoleFac                             $salesOrderCollectionFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \XShoppingSt\Mpshipping\Model\MpshippingDistFactory $mpshippingDistFactory
    ) {
        parent::__construct($context);
        $this->coreRegistry = $coreRegistry;
        $this->mpshippingDistFactory = $mpshippingDistFactory;
    }

    /**
     * @return void
     */
    public function execute()
    {
        $id=(int)$this->getRequest()->getParam('id');
        $distancesetModel=$this->mpshippingDistFactory->create();
        if ($id) {
            $distancesetModel->load($id);
            if (!$distancesetModel->getEntityId()) {
                $this->messageManager->addError(__('This Shiiping Rule is no longer exists.'));
                $this->_redirect('mpshipping/*/');
                return;
            }
        }

        $this->coreRegistry->register('mpshippingDist_shipping', $distancesetModel);
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('XShoppingSt_Mpshipping::mpshipping');
        $resultPage->getConfig()->getTitle()->prepend(__('Shipping By Distance'));
        $resultPage->addContent(
            $resultPage
            ->getLayout()
            ->createBlock(
                \XShoppingSt\Mpshipping\Block\Adminhtml\Distanceset\Edit::class
            )
        );
        $resultPage->addLeft(
            $resultPage
            ->getLayout()
            ->createBlock(
                \XShoppingSt\Mpshipping\Block\Adminhtml\Distanceset\Edit\Tabs::class
            )
        );
          return $resultPage;
    }
}
