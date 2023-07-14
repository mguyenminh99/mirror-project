<?php

namespace Mpx\Marketplace\Plugin\Product;

use XShoppingSt\Marketplace\Controller\Product\SaveProduct;

class BeforeSaveProductData
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
     * @param \Mpx\Marketplace\Helper\CommonFunc $marketplaceHelperData
     */
    public function __construct(
        \Magento\Customer\Model\Session $customerSession,
        \Mpx\Marketplace\Helper\CommonFunc $marketplaceHelperData
    ) {
        $this->customerSession = $customerSession;
        $this->marketplaceHelperData = $marketplaceHelperData;
    }

    /**
     * Set data before save product
     *
     * @param SaveProduct $subject
     * @param string $sellerId
     * @param array $wholedata
     * @return array
     */
    public function beforeSaveProductData(SaveProduct $subject, $sellerId, $wholedata): array
    {
        $wholedata = $this->setSkuFormat($wholedata);
        return [$sellerId,$wholedata];
    }

    /**
     * Set sku format simple product and simple product of Configurable Product
     *
     * @param array $wholeData
     * @return array
     */
    private function setSkuFormat(array $wholeData)
    {
        $formattedSku = $this->marketplaceHelperData->formatSku($wholeData['product']['sku']);
        $wholeData['product']['sku'] = $formattedSku;

        if ($this->isConfigurableProduct($wholeData)) {
            $wholeData['variations-matrix'] = $this->setProductVariationSkuPrefix($wholeData['variations-matrix']);
        }

        return $wholeData;
    }

    /**
     * Set each simple product of Configurable Product Sku Prefix
     *
     * @param array $variationsMatrix
     * @return array
     */
    private function setProductVariationSkuPrefix(array $variationsMatrix)
    {
        foreach ($variationsMatrix as $key => $value) {
            $variationsMatrix[$key]['sku'] = $this->marketplaceHelperData->formatSku($value['sku']);
        }

        return $variationsMatrix;
    }


    /**
     *
     * check product is configurable
     *
     * @param array $wholeData
     * @return bool
     */
    private function isConfigurableProduct(array $wholeData){
        return (isset($wholeData['variations-matrix']) && !empty($wholeData['variations-matrix']));
    }

}
