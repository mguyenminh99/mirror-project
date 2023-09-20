<?php

namespace Mpx\ShipmentInstruction\Ui\Component\Listing\Column;

use Magento\Ui\Component\Listing\Columns\Column;

class ShippingDate extends Column
{
    /**
     * Prepare Data Source.
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $date = ($item['desired_delivery_date'] != '') ? date('Y年m月d日', strtotime($item['desired_delivery_date'])) : '';
                $time = ($item['desired_delivery_time'] != '') ? $item['desired_delivery_time'] : '' ;
                $combinedValue = $date . '  ' . $time;
                $item[$this->getData('name')] = $combinedValue;
            }
        }

        return $dataSource;
    }
}
