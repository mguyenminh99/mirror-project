<?php
namespace Mpx\ShipmentInstruction\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Mpx\ShipmentInstruction\Helper\Data as HelperData;

class InvoiceType extends Column
{
    /**
     * @var HelperData
     */
    protected $helperData;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param HelperData $helperData
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        HelperData $helperData,
        array $components = [],
        array $data = []
    ) {
        $this->helperData = $helperData;
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
        $deliveryFormatList = $this->helperData->getListInvoiceType();
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                foreach ($deliveryFormatList as $key => $value) {
                    $key = strval($key);
                    if ($item['format'] === $key) {
                        $item['format'] = __($value);
                    }
                }
            }
        }
        return $dataSource;
    }
}
