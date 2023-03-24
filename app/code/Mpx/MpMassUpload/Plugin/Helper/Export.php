<?php
namespace Mpx\MpMassUpload\Plugin\Helper;

class Export
{
    /**
     * @var \Mpx\Marketplace\Helper\Data
     */
    protected $marketplaceHelperData;

     /**
     * @param \Mpx\Marketplace\Helper\Data $marketplaceHelperData
     */
    public function __construct(
        \Mpx\Marketplace\Helper\Data $marketplaceHelperData
    ) {
        $this->marketplaceHelperData = $marketplaceHelperData;
    }

    public function afterExportProducts(\Webkul\MpMassUpload\Helper\Export $subject, $result){
        foreach($result[1] as $key => $product){
            $result[1][$key]['sku'] =  $this->marketplaceHelperData->getSkuWithoutPrefix($product['商品番号']);
        }
        return $result;
    }
}
