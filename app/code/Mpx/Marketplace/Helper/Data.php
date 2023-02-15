<?php

namespace Mpx\Marketplace\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;

class Data extends AbstractHelper
{
    const UNICODE_HYPHEN_MINUS = "\u{002D}";
    const SKU_PREFIX_LENGTH = 4;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    public function __construct(
        Context $context,
        \Magento\Customer\Model\Session $customerSession
    ) {
        $this->customerSession = $customerSession;
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
}
