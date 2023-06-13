<?php

namespace Mpx\Marketplace\Helper;

use Magento\Authorization\Model\UserContextInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Webkul\Marketplace\Helper\Data as HelperData;
use Webkul\Marketplace\Model\SellerFactory as MpSeller;
use Magento\Store\Model\ScopeInterface;
use Mpx\Marketplace\Helper\Constant;

class CommonFunc extends AbstractHelper
{
    /**
     * @var Session
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
     * @var UserContextInterface
     */
    protected $userContext;

    /**
     * @param Context $context
     * @param Session $customerSession
     * @param MpSeller $mpSeller
     * @param ScopeConfigInterface $scopeConfig
     * @param HelperData $helper
     * @param UserContextInterface $userContext
     */
    public function __construct(
        Context $context,
        Session $customerSession,
        MpSeller                        $mpSeller,
        ScopeConfigInterface            $scopeConfig,
        HelperData       $helper,
        UserContextInterface $userContext
    ) {
        $this->customerSession = $customerSession;
        $this->mpSeller = $mpSeller;
        $this->scopeConfig = $scopeConfig;
        $this->helper = $helper;
        $this->userContext = $userContext;
        parent::__construct($context);
    }

    /**
     * Format Sku
     *
     * @param string $sku
     * @return string
     */
    public function formatSku($sku): string
    {
        $skuPrefix = $this->getSkuPrefix();
        return $skuPrefix . Constant::UNICODE_HYPHEN_MINUS . $sku;
    }

    /**
     * Get sku prefix Seller Id
     *
     * @return string
     */
    public function getSkuPrefix()
    {
        return str_pad($this->userContext->getUserId(), 3, "0", STR_PAD_LEFT);
    }

    /**
     * Get Sku Format
     *
     * @param string $sku
     * @return false|string
     */
    public function getSkuWithoutPrefix($sku)
    {
        return substr($sku, Constant::SKU_PREFIX_LENGTH);
    }

    /**
     * Get Seller Data
     *
     * @return AbstractDb|AbstractCollection
     * @return AbstractCollection|null
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
        return $this->scopeConfig->getValue(
            'mpx_web/settings_store/limit_seller',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Check Limit Seller
     *
     * @return bool
     */
    public function isRunOutOfSellerLimit(): bool
    {
        return ($this->getSellerData()->getSize() >= $this->getConfigLimitSeller());
    }

    /**
     * Check Seller Login
     *
     * @return bool
     */
    public function isSellerLogin(): bool
    {
        $sellerId = $this->customerSession->getCustomerId();
        $sellerCollection = $this->helper->getSellerCollectionObj($sellerId);
        foreach ($sellerCollection as $value) {
            if ($value->getIsSeller() == Constant::ENABLED_SELLER_STATUS) {
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
}
