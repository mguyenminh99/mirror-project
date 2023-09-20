<?php

namespace Mpx\ShipmentInstruction\Controller\ShipmentInstruction;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Mpx\Common\Helper\CommonFunc;

class Edit extends Action
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;

    /**
     * @var PageFactory
     */
    protected $_resultPageFactory;

    /**
     * @var \XShoppingSt\Marketplace\Helper\Data
     */
    protected $helperData;

    /**
     * @var CommonFunc
     */
    public $commonFunc;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \XShoppingSt\Marketplace\Helper\Data $helperData
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        \Magento\Customer\Model\Session $customerSession,
        \XShoppingSt\Marketplace\Helper\Data $helperData,
        CommonFunc $commonFunc
    ) {
        $this->_customerSession = $customerSession;
        $this->_resultPageFactory = $resultPageFactory;
        $this->helperData = $helperData;
        $this->commonFunc = $commonFunc;
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Default controller action
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        if ($this->commonFunc->isSellerStatusOpenning()) {
            $resultPage = $this->_resultPageFactory->create();
            if ($this->helperData->getIsSeparatePanel()) {
                $resultPage->addHandle('shipmentinstruction_layout2_shipmentinstruction_edit');
            }
             $resultPage->getConfig()->getTitle()->set(
                __('Edit Shipment Instruction')
            );
            return $resultPage;
        } else {
            return $this->resultRedirectFactory->create()->setPath(
                'marketplace/account/becomeseller',
                ['_secure' => $this->getRequest()->isSecure()]
            );
        }
    }
}
