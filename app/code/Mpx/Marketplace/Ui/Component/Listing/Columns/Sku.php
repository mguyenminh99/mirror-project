<?php
namespace Mpx\MarketPlace\Ui\Component\Listing\Columns;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class Sku extends Column
{
    /**
     * @var \Mpx\Marketplace\Helper\Data
     */
    protected $marketplaceHelperData;

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        \Mpx\Marketplace\Helper\Data $marketplaceHelperData,
        array $components = [],
        array $data = []
    )
    {
        $this->marketplaceHelperData = $marketplaceHelperData;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

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
                    $item[$fieldName] = $this->marketplaceHelperData->getSkuWithoutPrefix($item[$fieldName]);
                }
            }
        }
        return $dataSource;
    }
}
