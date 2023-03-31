<?php

namespace Mpx\Marketplace\Plugin\Product;

use Mpx\Marketplace\Helper\Constant;

class Verifysku
{

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

     /**
     * @var \Mpx\Marketplace\Helper\CommonFunc
     */
    protected $marketplaceHelperData;

    /**
     * @param \Magento\Customer\Model\Session $customerSession
     */
    public function __construct(
        \Magento\Customer\Model\Session $customerSession,
        \Mpx\Marketplace\Helper\CommonFunc $marketplaceHelperData
    ) {
        $this->customerSession = $customerSession;
        $this->marketplaceHelperData = $marketplaceHelperData;
    }

    /**
     * Set value sku format before check sku exits
     *
     * @param \Webkul\Marketplace\Controller\Product\Verifysku $subject
     * @return void
     */
    public function beforeExecute(\Webkul\Marketplace\Controller\Product\Verifysku $subject)
    {
        $params = $subject->getRequest()->getParams();
        $skuFormat = $this->marketplaceHelperData->formatSku($params['sku']);
        $params['sku'] = $skuFormat;
        $subject->getRequest()->setParams($params);
    }

    /**
     * Format Sku
     *
     * @param string $sku
     * @return string
     */
    public function formatSku($sku)
    {
        $sellerId = $this->customerSession->getCustomer()->getId();
        $skuPrefix = str_pad($sellerId, 3, "0", STR_PAD_LEFT);

        return $skuPrefix.Constant::UNICODE_HYPHEN_MINUS.$sku;
    }
}
