<?php
namespace Mpx\ShipmentInstruction\Helper;

use Magento\Framework\App\Helper\Context;
use Mpx\ShipmentInstruction\Model\ResourceModel\ShipmentInstruction\CollectionFactory;
use Magento\Framework\App\Response\Http\FileFactory;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var CollectionFactory
     */
    public $shipmentInstructionCollection;

    /**
     * @var FileFactory
     */
    protected $fileFactory;

    /**
     * @param CollectionFactory $shipmentInstructionCollection
     * @param Context $context
     */
    public function __construct(
        CollectionFactory $shipmentInstructionCollection,
        Context $context,
        FileFactory   $fileFactory
    )
    {
        $this->fileFactory = $fileFactory;
        $this->shipmentInstructionCollection = $shipmentInstructionCollection;
        parent::__construct($context);
    }

    /**
     * @param $order
     * @return array
     */
    public function getItemsToCreateShipmentInstruction($order){
        $collection = [];
        foreach ($order->getAllItems() as $item){
            if ($this->isShippedItem($item)) {
                continue;
            }

            $collectionShipment = $this->shipmentInstructionCollection->create()->addFilterOrderItem($order->getIncrementId(), $item->getSku());
            if(!$collectionShipment->getSize()){
                $collection[] = $item;
            }
        }
        return $collection;
    }

    /**
     * @param $order
     * @return bool
     */
    public function canCreateShipmentInstruction($order)
    {
        $orderItems = $order->getAllItems();

        foreach ($orderItems as $item){
            if(!$this->isShippedItem($item)){
                $instructionCollection = $this->shipmentInstructionCollection->create()->addFilterOrderItem($order->getIncrementId(), $item->getSku());

                if (!$instructionCollection->getSize()) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param \Magento\Sales\Model\Order\Item $orderItem
     * @return bool
     */
    public function isShippedItem($orderItem)
    {
        if ((int)$orderItem->getQtyOrdered() >= (int)$orderItem->getQtyShipped()) {
            return false;
        }

        return true;
    }

    /**
     * Create Csv file from grid data
     *
     * @param $exportData
     * @return \Magento\Framework\App\ResponseInterface
     * @throws \Exception
     */
    public function createCsvFileFromData($exportData)
    {
        $content = $this->arrayToCsv($exportData);

        return $this->fileFactory->create('export.csv', $content, 'var');
    }

    /**
     * Convert Array to Csv
     *
     * @param $array
     * @return string
     */
    private function arrayToCsv($array)
    {
        $csv = '';
        foreach ($array as $row) {
            $csv .= '"' . implode('","', $row) . '"' . "\n";
        }
        return $csv;
    }

    /**
     * Get List Delivery Format
     *
     * @return string[]
     */
    public function getListInvoiceType()
    {
        return [
            '0' => 'Prepayment',
            '2' => 'Collect',
            '3' => 'DM service',
            '4' => 'Time',
            '5' => 'COD',
            '6' => 'Prepayment (multi accounts)',
            '7' => 'Nekoposu',
            '8' => 'Fast delivery compact',
            '9' => 'Fast delivery compact collect'
        ];
    }
}
