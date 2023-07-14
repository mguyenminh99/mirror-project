<?php
namespace XShoppingSt\Marketplace\Observer;

use Magento\Framework\Event\ObserverInterface;

/**
 * XShoppingSt Marketplace MpSellertEditProfilePredispatchObserver Observer.
 */
class MpSellertEditProfilePredispatchObserver implements ObserverInterface
{
    /**
     * @var \XShoppingSt\Marketplace\Helper\Data
     */
    protected $helper;

    /**
     * @var \Magento\Framework\App\ResponseFactory
     */
    protected $responseFactory;

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $url;

    /**
     * @param \XShoppingSt\Marketplace\Helper\Data $helper
     * @param \Magento\Framework\App\ResponseFactory $responseFactory
     * @param \Magento\Framework\UrlInterface $url
     */
    public function __construct(
        \XShoppingSt\Marketplace\Helper\Data $helper,
        \Magento\Framework\App\ResponseFactory $responseFactory,
        \Magento\Framework\UrlInterface $url
    ) {
        $this->helper = $helper;
        $this->responseFactory = $responseFactory;
        $this->url = $url;
    }

    /**
     * Marketplace Account EditProfile Controller Predispatch event handler.
     *
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if (!$this->helper->getSellerProfileDisplayFlag()) {
            $redirectUrl = $this->url->getUrl('marketplace/account/dashboard');
            $this->responseFactory->create()->setRedirect($redirectUrl)->sendResponse();
            $observer->getControllerAction()->getResponse()->setRedirect($redirectUrl);
            return $this;
        }
    }
}
