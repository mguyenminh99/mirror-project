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
use Magento\Framework\App\Config\ScopeConfigInterface;
use Webkul\Marketplace\Model\SellerFactory as MpSeller;
use Magento\Store\Model\ScopeInterface;
use Webkul\Marketplace\Helper\Data as HelperData;
use Mpx\Marketplace\Helper\Constant;

class CommonFunc extends AbstractHelper
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var MpSeller
     */
    protected $mpSeller;

    /**
     * @var ScopeConfigInterface
     */
    public $scopeConfig;

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
     * @param \Magento\Customer\Model\Session $customerSession
     * @param MpSeller $mpSeller
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     * @param LoggerInterface $logger
     * @param CheckoutSessionFactory $checkoutSessionFactory
     * @param CartRepositoryInterface $cartRepository
     * @param \Webkul\MpTimeDelivery\Helper\Data $_helper
     * @param ManagerInterface $messageManager
     * @param Cart $cart
     * @param Context $context
     * @param HelperData $helper
     */
    public function __construct(
        \Magento\Customer\Model\Session    $customerSession,
        MpSeller                           $mpSeller,
        ScopeConfigInterface               $scopeConfig,
        StoreManagerInterface              $storeManager,
        LoggerInterface                    $logger,
        checkoutSessionFactory             $checkoutSessionFactory,
        CartRepositoryInterface            $cartRepository,
        \Webkul\MpTimeDelivery\Helper\Data $_helper,
        ManagerInterface                   $messageManager,
        Cart                               $cart,
        Context                            $context,
        HelperData                         $helper
    ) {
        $this->helper = $helper;
        $this->customerSession = $customerSession;
        $this->mpSeller = $mpSeller;
        $this->scopeConfig = $scopeConfig;
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
    /**
     * Validate time with matching input format
     *
     * @param string $dateTime
     * @param string $format
     * @return bool
     */
    public function validateTimeFormat(string $dateTime, string $format = ''): bool
    {
        if (!$format) {
            return false;
        }
        $validator = new \Zend_Validate_Date($format);
        if ($validator->isValid($dateTime)) {
            return true;
        }
        return false;
    }

//End Mpx_Mpshipping

//Start Mpx_Marketplace
    /**
     * Format Sku
     *
     * @param string $sku
     * @return string
     */
    public function formatSku($sku)
    {
        $skuPrefix = $this->getSkuPrefix();
        return $skuPrefix . Constant::UNICODE_HYPHEN_MINUS . $sku;
    }

    /**
     * Get sku prefix
     *
     * @return string
     */
    public function getSkuPrefix()
    {
        $sellerId = $this->customerSession->getCustomer()->getId();
        $skuPrefix = str_pad($sellerId, 3, "0", STR_PAD_LEFT);
        return $skuPrefix;
    }

    /**
     * Get Sku Format
     *
     * @param string $sku
     * @return false|string
     */
    public function getUnformattedSku($sku)
    {
        return substr($sku, Constant::SKU_PREFIX_LENGTH);
    }

    /**
     * Get Seller Data
     *
     * @return \Magento\Framework\Data\Collection\AbstractDb
     * @return \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection|null
     */

    public function getSellerData()
    {
        $sellerData = $this->mpSeller->create()->getCollection();
        $sellerData->getSelect()->join(
            'customer_entity as ce',
            'main_table.seller_id = ce.entity_id',
            ['email' => 'email']
        );
        $sellerData->addFieldToFilter('is_seller', 1);
        $sellerData->getSelect()->group('email');
        return $sellerData;
    }

    /**
     * Get Config Limit Seller
     */
    public function getConfigLimitSeller()
    {
        $limit_seller = $this->scopeConfig->getValue(
            'mpx_web/settings_store/limit_seller',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        return $limit_seller;
    }

    /**
     * Check Limit Seller
     *
     * @return bool
     */
    public function isRunOutOfSellerLimit()
    {
        return ($this->getSellerData()->getSize() >= $this->getConfigLimitSeller());
    }

    /**
     * Check Seller Login
     *
     * @return bool
     */
    public function isSellerLogin()
    {
        $sellerId = $this->customerSession->getCustomerId();
        $sellerCollection = $this->helper->getSellerCollectionObj($sellerId);
        foreach ($sellerCollection as $value) {
            if ($value->getIsSeller() == self::ENABLED_SELLER_STATUS) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get Marketplace Name
     *
     * @return mixed
     */
    public function getMarketplaceName()
    {
        return $this->scopeConfig->getValue(
            Constant::MARKETPLACE_NAME_CONFIG_PATH,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get From Mail Address
     *
     * @return mixed
     */
    public function getFromMailAddress()
    {
        return $this->scopeConfig->getValue(
            Constant::FROM_MAIL_ADDRESS_CONFIG_PATH,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get Xsadmin Mail Address
     *
     * @return mixed
     */
    public function getXsadminMailAddress()
    {
        return $this->scopeConfig->getValue(
            Constant::XS_ADMIN_MAIL_ADDRESS_CONFIG_PATH,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get System Admin Mail Address
     *
     * @return mixed
     */
    public function getSystemAdminMailAddress()
    {
        return $this->scopeConfig->getValue(
            Constant::SYSTEM_ADMIN_MAIL_ADDRESS_CONFIG_PATH,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get System Notice Mail From Address
     *
     * @return mixed
     */
    public function getSystemNoticeMailFromAddress()
    {
        return $this->scopeConfig->getValue(
            Constant::SYSTEM_NOTICE_MAIL_FROM_ADDRESS_CONFIG_PATH,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get marketplace id config
     *
     * @return mixed
     */
    public function getMarketPlaceId()
    {
        return $this->scopeConfig->getValue(Constant::MARKETPLACE_ID_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
    }

//End Mpx_Marketplace
}
