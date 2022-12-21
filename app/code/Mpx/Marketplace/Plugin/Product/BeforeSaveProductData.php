<?php

namespace Mpx\Marketplace\Plugin\Product;

use Webkul\Marketplace\Controller\Product\SaveProduct;

class BeforeSaveProductData
{
    private const UNICODE_HYPHEN_MINUS = "\u{002D}";

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @param \Magento\Customer\Model\Session $customerSession
     */
    public function __construct(
        \Magento\Customer\Model\Session $customerSession
    ) {
        $this->customerSession = $customerSession;
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
        $formattedSku = $this->formatSku($wholeData['product']['sku']);
        $wholeData['product']['sku'] = $formattedSku;

        if (isset($wholeData['variations-matrix']) && !empty($wholeData['variations-matrix'])) {
            $dataSimpleProduct =  $wholeData['variations-matrix'];
            foreach ($dataSimpleProduct as $key => $value) {
                $formattedSku = $this->formatSku($value['sku']);
                $wholeData['variations-matrix'][$key]['sku'] = $formattedSku;
            }
        }

        return $wholeData;
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

        return $skuPrefix.self::UNICODE_HYPHEN_MINUS.$sku;
    }
}
