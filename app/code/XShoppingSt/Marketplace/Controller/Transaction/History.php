<?php
namespace XShoppingSt\Marketplace\Controller\Transaction;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\RequestInterface;
use XShoppingSt\Marketplace\Model\ResourceModel\Sellertransaction\CollectionFactory;
use XShoppingSt\Marketplace\Helper\Data as HelperData;
use XShoppingSt\Marketplace\Helper\Notification as NotificationHelper;
use XShoppingSt\Marketplace\Model\Notification;
use Magento\Customer\Model\Url as CustomerUrl;

/**
 * XShoppingSt Marketplace Transaction History Controller.
 */
class History extends Action
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
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var HelperData
     */
    protected $helper;

    /**
     * @var NotificationHelper
     */
    protected $notificationHelper;

    /**
     * @var CustomerUrl
     */
    protected $customerUrl;

    /**
     * @param Context                           $context
     * @param PageFactory                       $resultPageFactory
     * @param \Magento\Customer\Model\Session   $customerSession
     * @param CollectionFactory                 $collectionFactory
     * @param HelperData                        $helper
     * @param NotificationHelper                $notificationHelper
     * @param CustomerUrl                       $customerUrl
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        \Magento\Customer\Model\Session $customerSession,
        CollectionFactory $collectionFactory,
        HelperData $helper,
        NotificationHelper $notificationHelper,
        CustomerUrl $customerUrl
    ) {
        $this->_customerSession = $customerSession;
        $this->_resultPageFactory = $resultPageFactory;
        $this->collectionFactory = $collectionFactory;
        $this->helper = $helper;
        $this->notificationHelper = $notificationHelper;
        $this->customerUrl = $customerUrl;
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
        $loginUrl = $this->customerUrl->getLoginUrl();

        if (!$this->_customerSession->authenticate($loginUrl)) {
            $this->_actionFlag->set('', self::FLAG_NO_DISPATCH, true);
        }

        return parent::dispatch($request);
    }

    /**
     * Default customer account page.
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $helper = $this->helper;
        $isPartner = $helper->isSeller();
        if ($isPartner == 1) {
            /** @var \Magento\Framework\View\Result\Page $resultPage */
            $resultPage = $this->_resultPageFactory->create();
            if ($helper->getIsSeparatePanel()) {
                $resultPage->addHandle('marketplace_layout2_transaction_history');
            }
            $resultPage->getConfig()->getTitle()->set(
                __('Marketplace Seller Transactions')
            );
            /**
             * update notification for transaction history
             */
            $collection = $this->collectionFactory->create()
            ->addFieldToFilter(
                'seller_id',
                $helper->getCustomerId()
            );
            $type = Notification::TYPE_TRANSACTION;
            $this->notificationHelper->updateNotificationCollection(
                $collection,
                $type
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