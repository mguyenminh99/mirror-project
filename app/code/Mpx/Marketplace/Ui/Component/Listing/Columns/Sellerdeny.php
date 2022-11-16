<?php

namespace Mpx\Marketplace\Ui\Component\Listing\Columns;

class Sellerdeny extends \Webkul\Marketplace\Ui\Component\Listing\Columns\Sellerdeny
{
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
                $item[$fieldName.'_html'] = "<button class='button'><span>".__('Deny')."</span></button>";
                $item[$fieldName.'_title'] = __('What is the reason to deny this seller');
                $item[$fieldName.'_submitlabel'] = __('Submit');
                $item[$fieldName.'_cancellabel'] = __('Reset');
                $item[$fieldName.'_sellerid'] = $item['seller_id'];

                $item[$fieldName.'_formaction'] = $this->urlBuilder->getUrl('marketplace/seller/deny');
            }
        }

        return $dataSource;
    }
}
