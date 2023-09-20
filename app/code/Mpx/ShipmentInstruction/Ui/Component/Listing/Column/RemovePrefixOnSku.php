<?php

namespace Mpx\ShipmentInstruction\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Mpx\Marketplace\Helper\CommonFunc;

class RemovePrefixOnSku extends Column
{
    /**
     * @var CommonFunc
     */
    protected $commonFunc;

    public function __construct(
        CommonFunc $commonFunc,
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = []
    )
    {
        $this->commonFunc = $commonFunc;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

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
                $item[$this->getData('name')] = $this->commonFunc->getSkuWithoutPrefix($item[$this->getData('name')]);
            }
        }

        return $dataSource;
    }
}
