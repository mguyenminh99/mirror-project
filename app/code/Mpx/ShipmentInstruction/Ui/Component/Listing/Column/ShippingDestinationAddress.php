<?php

namespace Mpx\ShipmentInstruction\Ui\Component\Listing\Column;

use Magento\Ui\Component\Listing\Columns\Column;

class ShippingDestinationAddress extends Column
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
                $combinedValue = $item['destination_region'] . ' ' . $item['destination_city'] . ' ' . $item['destination_street'];
                $item[$this->getData('name')] = $combinedValue;
            }
        }

        return $dataSource;
    }
}