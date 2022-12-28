<?php

namespace Mpx\Catalog\Ui\Component\Listing\Columns;

use Magento\Ui\Component\Listing\Columns\Column;

class Refund extends Column
{

    /**
     * Prepare Column Quantity Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item[$fieldName])) {
                    $item[$fieldName] = '￥'.(int)$item[$fieldName];
                }
            }
        }
        return $dataSource;
    }
}
