<?php

namespace Mpx\Marketplace\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Checkout\Model\SessionFactory as CheckoutSessionFactory;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Message\ManagerInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Checkout\Model\Cart;
use Psr\Log\LoggerInterface;
use Magento\Store\Model\StoreManagerInterface;

class CommonFunc extends AbstractHelper
{

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var ManagerInterface
     */
    protected $messageManager;

    /**
     * @var CheckoutSessionFactory
     */
    protected $checkoutSessionFactory;

    /**
     * @var CartRepositoryInterface
     */
    protected $cartRepository;

    /**
     * @var \Webkul\MpTimeDelivery\Helper\Data
     */
    protected $_helper;

    /**
     * @var Cart
     */
    protected $cart;

    /**
     * @param StoreManagerInterface $storeManager
     * @param LoggerInterface $logger
     * @param CheckoutSessionFactory $checkoutSessionFactory
     * @param CartRepositoryInterface $cartRepository
     * @param \Webkul\MpTimeDelivery\Helper\Data $_helper
     * @param ManagerInterface $messageManager
     * @param Cart $cart
     * @param Context $context
     */
    public function __construct(
        StoreManagerInterface              $storeManager,
        LoggerInterface                    $logger,
        checkoutSessionFactory             $checkoutSessionFactory,
        CartRepositoryInterface            $cartRepository,
        \Webkul\MpTimeDelivery\Helper\Data $_helper,
        ManagerInterface                   $messageManager,
        Cart                               $cart,
        Context                            $context
    ) {
        $this->storeManager = $storeManager;
        $this->logger = $logger;
        $this->checkoutSessionFactory = $checkoutSessionFactory;
        $this->cartRepository = $cartRepository;
        $this->_helper = $_helper;
        $this->messageManager = $messageManager;
        $this->cart = $cart;
        parent::__construct($context);
    }

//    Start Mpx_Checkout
    /**
     * Count Seller In Cart
     *
     * @return int
     */
    public function countSellerInCart(): int
    {
        try {
            $sellerIds = [];
            if ($this->checkoutSessionFactory->create()->getQuote()->getId()) {
                $quote = $this->cartRepository->get($this->checkoutSessionFactory->create()->getQuote()->getId());
                foreach ($quote->getAllItems() as $item) {
                    $mpAssignProductId = $this->_helper->getAssignProduct($item);
                    $sellerIds[] = $this->_helper->getSellerId($mpAssignProductId, $item->getProductId());
                }

            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage('Error cannot count seller cart');
        }

        return count(array_unique($sellerIds));
    }

//    End Mpx_Checkout

//Start Mpx_Sales
    /**
     * Get Url
     *
     * @param string $shopPageUrl
     * @return string
     */
    public function getUrl(string $shopPageUrl): string
    {
        try {
            $store = $this->storeManager->getStore();
            if ($store) {
                $url =  $store->getBaseUrl();
                return $url."marketplace/seller/profile/shop/".$shopPageUrl;
            }
        } catch (\Exception $exception) {
            $this->logger->critical($exception);
            return "";
        }
        return "";
    }

//End Mpx_Sales

//Start Mpx_Mpshipping
    /**
     * Check if number is decimal
     *
     * @param string $val
     * @return bool
     */
    public function isDecimal(string $val): bool
    {
        return is_numeric($val) && floor($val) != $val;
    }

//End Mpx_Mpshipping
}
