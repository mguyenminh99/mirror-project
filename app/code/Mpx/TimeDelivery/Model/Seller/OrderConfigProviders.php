<?php
namespace Mpx\TimeDelivery\Model\Seller;

use Magento\Customer\Model\SessionFactory;
use Mpx\Marketplace\Helper\CommonFunc;
use XShoppingSt\MpTimeDelivery\Model\ResourceModel\TimeSlotOrder\CollectionFactory;


class OrderConfigProviders extends \XShoppingSt\MpTimeDelivery\Model\Seller\OrderConfigProviders
{
    /**
     * @var CommonFunc
     */
    public $helperCommonFunc;

    /**
     * @var CollectionFactory
     */
    public $timeSlotCollection;

    public function __construct(
        SessionFactory $customerSessionFactory,
        CollectionFactory $timeSlotCollection,
        CommonFunc $helperCommonFunc
    )
    {
        $this->helperCommonFunc = $helperCommonFunc;
        parent::__construct($customerSessionFactory, $timeSlotCollection);
    }

    /**
     * @return object
     */
    public function getCollection()
    {
        $collection = $this->timeSlotCollection->create()
            ->getDeliveryOrderCollection()
            ->addFieldToFilter('seller_id', $this->helperCommonFunc->getOriginSellerId($this->_getCustomer()->getId()))
            ->setOrder('selected_date', 'DESC');

        return $collection;
    }

}
