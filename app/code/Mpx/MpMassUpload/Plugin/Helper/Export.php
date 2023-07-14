<?php
namespace Mpx\MpMassUpload\Plugin\Helper;

class Export
{
    /**
     * @var \Mpx\Marketplace\Helper\CommonFunc
     */
    protected $marketplaceHelperData;

     /**
     * @param \Mpx\Marketplace\Helper\CommonFunc $marketplaceHelperData
     */
    public function __construct(
        \Mpx\Marketplace\Helper\CommonFunc $marketplaceHelperData
    ) {
        $this->marketplaceHelperData = $marketplaceHelperData;
    }

    public function afterExportProducts(\XShoppingSt\MpMassUpload\Helper\Export $subject, $result){
        foreach($result[1] as $key => $product){
            $result[1][$key]['sku'] =  $this->marketplaceHelperData->getSkuWithoutPrefix($product['商品番号']);
        }
        return $result;
    }
}
