<?php

namespace Mpx\ShipmentInstruction\Ui\DataProvider;

use Mpx\ShipmentInstruction\Model\ResourceModel\ShipmentInstruction\CollectionFactory;
use XShoppingSt\Marketplace\Helper\Data as HelperData;
/**
 * Class ShipmentInstructionDataProvider
 */
class ShipmentInstructionDataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var \Mpx\ShipmentInstruction\Model\ResourceModel\ShipmentInstruction\Collection
     */
    protected $collection;

    public function __construct(
       $name,
       $primaryFieldName,
       CollectionFactory $collectionFactory,
       HelperData $helperData,
       $requestFieldName,
       array $meta = [],
       array $data = []
    ) {
       parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
       $sellerId = $helperData->getCustomerId();
       $this->collection = $collectionFactory->create()->addFieldToFilter('seller_id' ,['eq' => $sellerId]);
    }

    public function getSearchResult()
    {
        return $this->collection->getData();
    }
}
