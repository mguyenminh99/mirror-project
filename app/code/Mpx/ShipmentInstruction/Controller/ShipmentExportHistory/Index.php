<?php
namespace Mpx\ShipmentInstruction\Controller\ShipmentExportHistory;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Customer\Model\Url as CustomerUrl;
use Mpx\Common\Helper\CommonFunc;

class Index extends Action
{
    /**
     * @var CommonFunc
     */
    public $commonFunc;


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
     * @var CustomerUrl
     */
    protected $customerUrl;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \XShoppingSt\Marketplace\Helper\Data $helperData
     * @param CustomerUrl $customerUrl
     * @param CommonFunc $commonFunc
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        \Magento\Customer\Model\Session $customerSession,
        \XShoppingSt\Marketplace\Helper\Data $helperData,
        CustomerUrl $customerUrl,
        CommonFunc $commonFunc
    ) {
        $this->_customerSession = $customerSession;
        $this->_resultPageFactory = $resultPageFactory;
        $this->helperData = $helperData;
        $this->customerUrl = $customerUrl;
        $this->commonFunc = $commonFunc;
        parent::__construct($context);
    }

    /**
     * Check customer authentication.
     *
     * @param RequestInterface $request
     *
     * @return \Magento\Framework\App\ResponseInterface
     */
    public function dispatch(RequestInterface $request)
    {
        if (!$this->_customerSession->authenticate($this->customerUrl->getLoginUrl())) {
            $this->_actionFlag->set('', self::FLAG_NO_DISPATCH, true);
        }

        return parent::dispatch($request);
    }

    /**
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        if ($this->commonFunc->isSellerStatusOpenning()) {
            $resultPage = $this->_resultPageFactory->create();
            if ($this->helperData->getIsSeparatePanel()) {
                $resultPage->addHandle('shipmentinstruction_layout2_shipmentexporthistory_index');
            }
            $resultPage->getConfig()->getTitle()->set(
                __('CSV output history for invoice labels')
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
