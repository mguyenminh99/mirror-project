<?php
namespace XShoppingSt\Mpshipping\Controller\Distanceset ;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Customer\Model\Url;

class View extends Action
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
     * @var Magento\Customer\Model\Url
     */
    protected $_customerUrl;
    /**
     * @var XShoppingSt\Marketplace\Helper\Data
     */
    protected $_marketplaceHelperData;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        \Magento\Customer\Model\Session $customerSession,
        \XShoppingSt\Marketplace\Helper\Data $marketplaceHelperData,
        \XShoppingSt\Mpshipping\Helper\Data $mpShippingHelper,
        \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory,
        Url $customerUrl
    ) {
        parent::__construct($context);
        $this->_customerSession = $customerSession;
        $this->_resultPageFactory = $resultPageFactory;
        $this->_marketplaceHelperData = $marketplaceHelperData;
        $this->mpShippingHelper = $mpShippingHelper;
        $this->resultForwardFactory = $resultForwardFactory;
        $this->_customerUrl = $customerUrl;
    }

    /**
     * Check customer authentication
     *
     * @param RequestInterface $request
     * @return \Magento\Framework\App\ResponseInterface
     */
    public function dispatch(RequestInterface $request)
    {
        $loginUrl = $this->_customerUrl->getLoginUrl();
        if (!$this->_customerSession->authenticate($loginUrl)) {
            $this->_actionFlag->set('', self::FLAG_NO_DISPATCH, true);
        }
        return parent::dispatch($request);
    }

    /**
     * Shipping rate view page
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $isPartner = $this->_marketplaceHelperData->isSeller();
        if ($isPartner == 1) {
            if ($this->mpShippingHelper->getDistanceShippingStatus()) {
                $resultPage = $this->_resultPageFactory->create();
                if ($this->_marketplaceHelperData->getIsSeparatePanel()) {
                    $resultPage->addHandle('mpshipping_layout2_distanceset_view');
                }
                $resultPage->getConfig()->getTitle()->set(__('Shipping By Distnace'));
                return $resultPage;
            } else {
                $resultForward = $this->resultForwardFactory->create();
                $resultForward->forward('noroute');
                return $resultForward;
            }
        } else {
            return $this->resultRedirectFactory->create()->setPath(
                'marketplace/account/becomeseller',
                ['_secure' => $this->getRequest()->isSecure()]
            );
        }
    }
}
