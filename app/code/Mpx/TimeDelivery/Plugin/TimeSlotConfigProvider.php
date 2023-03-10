<?php
namespace Mpx\TimeDelivery\Plugin;

class TimeSlotConfigProvider
{
    /**
    * @var \Webkul\Marketplace\Model\Seller
    */
    public $sellerFactory;

    public function __construct(
        \Webkul\Marketplace\Model\SellerFactory $sellerFactory
    )
    {
        $this->sellerFactory = $sellerFactory;
    }

    public function afterGetConfig(
        \Webkul\MpTimeDelivery\Model\TimeSlotConfigProvider $subject,
        $result
         ){

        if(isset($result['seller'][0])){
            $result['seller'][0]['shop_title'] = __('Admin');
            return $result;
        }

        foreach($result['seller'] as $key => $sellerTimeSlotData){
            $marketplaceSellerCollection = $this->sellerFactory->create()
                ->getCollection()
                ->addFieldToFilter('seller_id',['eq' => $key])
                ->addFieldToFilter('shop_title',['neq' => 'NULL'])
                ->addFieldToFilter('store_id', ['eq' => 1]);
            $result['seller'][$key]['shop_title'] = $marketplaceSellerCollection->getLastItem()->getShopTitle();
        }

        return $result;
    }
}
