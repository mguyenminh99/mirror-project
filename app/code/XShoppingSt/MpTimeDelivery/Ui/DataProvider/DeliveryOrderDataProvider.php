<?php
namespace XShoppingSt\MpTimeDelivery\Ui\DataProvider;

use XShoppingSt\Marketplace\Helper\Data as HelperData;
use XShoppingSt\MpTimeDelivery\Model\Seller\OrderConfigProviders;

/**
 * Slot Order Data Provider
 */
class DeliveryOrderDataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var XShoppingSt\MpTimeDelivery\Model\Seller\OrderConfigProviders
     */
    protected $orderConfigProviders;

    /**
     * @var HelperData
     */
    public $helperData;

    /**
     * Construct
     *
     * @param string                $name
     * @param string                $primaryFieldName
     * @param string                $requestFieldName
     * @param HelperData            $helperData
     * @param OrderConfigProviders  $orderConfigProviders,
     * @param array                 $meta
     * @param array                 $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        HelperData $helperData,
        OrderConfigProviders $orderConfigProviders,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $collectionData = $orderConfigProviders->getCollection();
        $this->collection = $collectionData;
    }
}
