<?php
namespace Mpx\ShipmentInstruction\Controller\ShipmentInstruction;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Mpx\Common\Helper\CommonFunc;
use \XShoppingSt\Marketplace\Helper\Data;

class Duplicate extends Action
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var CommonFunc
     */
    public $commonFunc;

    /**
     * @var Data
     */
    protected $helperData;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param CommonFunc $commonFunc
     * @param Data $helperData
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        CommonFunc $commonFunc,
        Data $helperData
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->commonFunc = $commonFunc;
        $this->helperData = $helperData;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\ResultInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute()
    {
        if ($this->commonFunc->isSellerStatusOpenning()) {
            $resultPage = $this->resultPageFactory->create();
            if ($this->helperData->getIsSeparatePanel()) {
                $resultPage->addHandle('shipmentinstruction_layout2_shipmentinstruction_duplicate');
            }
            $resultPage->getConfig()->getTitle()->set(
                __('Duplicate Shipment Instruction')
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
