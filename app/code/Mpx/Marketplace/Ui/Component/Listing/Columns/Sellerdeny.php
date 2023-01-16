<?php

namespace Mpx\Marketplace\Ui\Component\Listing\Columns;

class Sellerdeny extends \Webkul\Marketplace\Ui\Component\Listing\Columns\Sellerdeny
{
    const TEMPORARILY_SUSPENDED_STATUS = 3;
    const ENABLE_SELLER = "enable_seller";
    /**
     * Prepare Data Source.
     *
     * @param array $dataSource
     *
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as &$item) {
                if($item['is_seller'] == self::TEMPORARILY_SUSPENDED_STATUS){
                    $item[$fieldName.'_html'] = "<button class='button'><span>".__('Reopen')."</span></button>";
                    $item[$fieldName.'_title'] = __('Do you want to reopen your store?');
                    $item[$fieldName.'_submitlabel'] = __('Reopen');
                    $item[$fieldName.'_cancellabel'] = __('Cancel');
                    $item[$fieldName.'_sellerid'] = $item['seller_id'];
                    $item[$fieldName.'_temporarily_suspended_status'] = self::TEMPORARILY_SUSPENDED_STATUS;

                    $item[$fieldName.'_formaction'] = $this->urlBuilder->getUrl('marketplace/seller/deny');
                }
                else{
                    $item[$fieldName.'_html'] = "<button class='button'><span>".__('Deny')."</span></button>";
                    $item[$fieldName.'_title'] = __('What is the reason to deny this seller');
                    $item[$fieldName.'_submitlabel'] = __('Submit');
                    $item[$fieldName.'_cancellabel'] = __('Reset');
                    $item[$fieldName.'_sellerid'] = $item['seller_id'];
                    $item[$fieldName.'seller_status_update_to'] = self::ENABLE_SELLER;

                    $item[$fieldName.'_formaction'] = $this->urlBuilder->getUrl('marketplace/seller/deny');
                }
            }
        }

        return $dataSource;
    }
}
