<?php
namespace XShoppingSt\Marketplace\Ui\Component\Listing\Columns;

class ProductStatus extends \Magento\Ui\Component\Listing\Columns\Column
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
                if (isset($item[$fieldName])) {
                    if ($item[$fieldName] == 1) {
                        $item[$fieldName] = '<a>'.$item[$fieldName].'</a>';
                    }
                }
            }
        }

        return $dataSource;
    }
}
