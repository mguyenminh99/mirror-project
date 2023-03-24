<?php

namespace Mpx\Marketplace\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Mpx\Marketplace\Controller\Adminhtml\Seller\Deny;
use Webkul\Marketplace\Helper\Data as HelperData;
use Webkul\Marketplace\Model\SellerFactory as MpSeller;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    const UNICODE_HYPHEN_MINUS = "\u{002D}";
    const SKU_PREFIX_LENGTH = 4;
    const MARKETPLACE_NAME_CONFIG_PATH = 'mpx_web/general/marketplaceName';
    const FROM_MAIL_ADDRESS_CONFIG_PATH = 'mpx_web/general/notificationEmail';
    const XS_ADMIN_MAIL_ADDRESS_CONFIG_PATH = 'mpx_web/general/xsadminEmail';
    const SYSTEM_ADMIN_MAIL_ADDRESS_CONFIG_PATH = 'mpx_web/general/systemAdminEmail';
    const SYSTEM_NOTICE_MAIL_FROM_ADDRESS_CONFIG_PATH = 'mpx_web/general/systemNotificationEmail';
    const MARKETPLACE_ID_CONFIG_PATH = 'mpx_web/general/marketplaceId';

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

    public function __construct(
        Context $context,
        \Magento\Customer\Model\Session $customerSession,
        MpSeller                        $mpSeller,
        ScopeConfigInterface            $scopeConfig,
        HelperData       $helper
    ) {
        $this->customerSession = $customerSession;
        $this->mpSeller = $mpSeller;
        $this->scopeConfig = $scopeConfig;
        $this->helper = $helper;
        parent::__construct($context);
    }

    /**
     * Format Sku
     *
     * @param string $sku
     * @return string
     */
    public function formatSku($sku)
    {
        $skuPrefix = $this->getSkuPrefix();
        return $skuPrefix.self::UNICODE_HYPHEN_MINUS.$sku;
    }

    /**
     * get sku prefix
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
    public function getSkuWithoutPrefix($sku)
    {
        return substr($sku, self::SKU_PREFIX_LENGTH);
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
     * @return bool
     */
    public function isRunOutOfSellerLimit()
    {
        return ( $this->getSellerData()->getSize() >= $this->getConfigLimitSeller() );
    }

    /**
     * @return bool
     */
    public function isSellerLogin()
    {
        $sellerId = $this->customerSession->getCustomerId();
        $sellerCollection = $this->helper->getSellerCollectionObj($sellerId);
        foreach ($sellerCollection as $value) {
            if ($value->getIsSeller() == Deny::ENABLED_SELLER_STATUS) {
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
            self::MARKETPLACE_NAME_CONFIG_PATH,
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
            self::FROM_MAIL_ADDRESS_CONFIG_PATH,
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
            self::XS_ADMIN_MAIL_ADDRESS_CONFIG_PATH,
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
            self::SYSTEM_ADMIN_MAIL_ADDRESS_CONFIG_PATH,
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
            self::SYSTEM_NOTICE_MAIL_FROM_ADDRESS_CONFIG_PATH,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * get marketplace id config
     *
     * @return mixed
     */
    public function getMarketPlaceId()
    {
        return $this->scopeConfig->getValue(self::MARKETPLACE_ID_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
    }

}
