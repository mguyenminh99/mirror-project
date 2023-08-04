<?php

namespace Mpx\Marketplace\Controller\Account;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\RequestInterface;
use XShoppingSt\Marketplace\Helper\Notification as NotificationHelper;
use XShoppingSt\Marketplace\Model\ResourceModel\Product\CollectionFactory;
use Magento\Customer\Model\Url as CustomerUrl;
use Mpx\Marketplace\Helper\Constant;
use Mpx\Marketplace\Helper\CommonFunc;

class CreateSeller extends Action
{
    /**
     * @var CommonFunc
     */
    protected $commonFuncHelper;

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
     * @var NotificationHelper
     */
    protected $notificationHelper;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var CustomerUrl
     */
    protected $customerUrl;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \XShoppingSt\Marketplace\Helper\Data $helperData
     * @param NotificationHelper $notificationHelper
     * @param CollectionFactory $collectionFactory
     * @param CustomerUrl $customerUrl
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        \Magento\Customer\Model\Session $customerSession,
        \XShoppingSt\Marketplace\Helper\Data $helperData,
        NotificationHelper $notificationHelper,
        CollectionFactory $collectionFactory,
        CustomerUrl $customerUrl,
        CommonFunc $commonFuncHelper
    )
    {
        $this->_customerSession = $customerSession;
        $this->_resultPageFactory = $resultPageFactory;
        $this->helperData = $helperData;
        $this->notificationHelper = $notificationHelper;
        $this->collectionFactory = $collectionFactory;
        $this->customerUrl = $customerUrl;
        $this->commonFuncHelper = $commonFuncHelper;
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
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        if($this->commonFuncHelper->isSubSeller($this->commonFuncHelper->getCustomerId())) {
            return $this->resultRedirectFactory->create()->setPath(
                'marketplace/account/dashboard',
                ['_secure' => $this->getRequest()->isSecure()]
            );
        }
        if ($this->helperData->isSeller() == Constant::SELLER_STATUS_OPENING) {
            $resultPage = $this->_resultPageFactory->create();
            if ($this->helperData->getIsSeparatePanel()) {
                    $resultPage->addHandle('mpx_layout2_account_createseller');
                    $resultPage->getConfig()->getTitle()->set(
                        __('Create Sub Account')
                    );
                    return $resultPage;
                }
            } else {
                return $this->resultRedirectFactory->create()->setPath(
                    'marketplace/account/becomeseller',
                    ['_secure' => $this->getRequest()->isSecure()]
                );
            }
        }
}
