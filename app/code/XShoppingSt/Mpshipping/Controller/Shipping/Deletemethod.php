<?php
namespace XShoppingSt\Mpshipping\Controller\Shipping;

use Magento\Framework\App\Action\Action;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\RequestInterface;
use XShoppingSt\Mpshipping\Model\MpshippingmethodFactory;
use Magento\Customer\Model\Url;
use XShoppingSt\Mpshipping\Model\MpshippingFactory;

class Deletemethod extends Action
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;
    /**
     * @var XShoppingSt\Mpshipping\Model\MpshippingmethodFactory
     */
    protected $_mpshippingMethod;
    /**
     * @var Magento\Customer\Model\Url
     */
    protected $_customerUrl;
    /**
     * @var XShoppingSt\Mpshipping\Model\MpshippingFactory
     */
    protected $_mpshippingModel;
    /**
     * @var XShoppingSt\Mpshipping\Helper\Data
     */
    protected $_mpshippingHelperData;

    /**
     * @param Context                 $context
     * @param Session                 $customerSession
     * @param MpshippingmethodFactory $shippingmethodFactory
     * @param Url                     $customerUrl
     * @param MpshippingFactory       $mpshippingModel
     */
    public function __construct(
        Context $context,
        Session $customerSession,
        MpshippingmethodFactory $shippingmethodFactory,
        Url $customerUrl,
        \XShoppingSt\Mpshipping\Helper\Data $mpshippingHelperData,
        MpshippingFactory $mpshippingModel
    ) {
        parent::__construct($context);
        $this->_customerSession = $customerSession;
        $this->_mpshippingMethod = $shippingmethodFactory;
        $this->_customerUrl = $customerUrl;
        $this->_mpshippingHelperData = $mpshippingHelperData;
        $this->_mpshippingModel = $mpshippingModel;
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
        $urlModel = $this->_customerUrl;
        $loginUrl = $urlModel->getLoginUrl();
        if (!$this->_customerSession->authenticate($loginUrl)) {
            $this->_actionFlag->set('', self::FLAG_NO_DISPATCH, true);
        }
        return parent::dispatch($request);
    }

    /**
     * Default Shipping Method.
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        try {
            $partnerId = $this->_mpshippingHelperData->getPartnerId();
            $fields = $this->getRequest()->getParams();
            if (!empty($fields)) {
                $shipMethodModel = $this->_mpshippingMethod->create()->load($fields['id']);
                if (!empty($shipMethodModel)) {
                    $shippingCollection = $this->_mpshippingModel
                        ->create()
                        ->getCollection()
                        ->addFieldToFilter('shipping_method_id', $fields['id'])
                        ->addFieldToFilter('partner_id', $partnerId);
                    foreach ($shippingCollection as $shipping) {
                        $shippingModel = $this->_mpshippingModel
                            ->create()
                            ->load($shipping->getMpshippingId());
                        if (!empty($shippingModel)) {
                            $shippingModel->delete();
                        }
                    }
                    $this->messageManager->addSuccess(__('Shipping Method is successfully Deleted!'));
                    return $resultRedirect->setPath('mpshipping/shipping/view');
                } else {
                    $this->messageManager->addError(__('No record Found!'));
                    return $resultRedirect->setPath('mpshipping/shipping/view');
                }
            } else {
                $this->messageManager->addSuccess(__('Please try again!'));
                return $resultRedirect->setPath('mpshipping/shipping/view');
            }
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
            return $resultRedirect->setPath('mpshipping/shipping/view');
        }
    }
}
