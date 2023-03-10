<?php

namespace Mpx\Marketplace\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Webkul\Marketplace\Model\SellerFactory as MpSeller;

class Data extends AbstractHelper
{
    const UNICODE_HYPHEN_MINUS = "\u{002D}";
    const SKU_PREFIX_LENGTH = 4;

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
        ScopeConfigInterface            $scopeConfig
    ) {
        $this->customerSession = $customerSession;
        $this->mpSeller = $mpSeller;
        $this->scopeConfig = $scopeConfig;
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
    public function getUnformattedSku($sku)
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

}
