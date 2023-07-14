<?php
namespace XShoppingSt\MpTimeDelivery\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Session\SessionManager;

class SalesOrderPlaceAfterObserver implements ObserverInterface
{
    /**
     * @var Magento\Framework\Session\SessionManager
     */
    protected $_session;

    /**
     * @var \XShoppingSt\MpTimeDelivery\Model\TimeSlotOrderFactory
     */
    protected $_timeSlotOrderFactory;

    /**
     * @var \XShoppingSt\MpTimeDelivery\Model\TimeSlotConfigFactory
     */
    protected $_timeSlotConfigFactory;

    /**
     * @var \XShoppingSt\MpTimeDelivery\Helper\Data
     */
    protected $helper;

    /**
     * @param \Magento\Framework\Stdlib\DateTime\DateTime           $dateTime
     * @param \XShoppingSt\MpTimeDelivery\Model\TimeSlotOrderFactory     $timeSlotOrderFactory
     * @param \XShoppingSt\MpTimeDelivery\Model\TimeSlotConfigFactory    $timeSlotConfigFactory,
     * @param \XShoppingSt\MpTimeDelivery\Helper\Data                    $helper
     * @param SessionManager                                        $session
     */
    public function __construct(
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime,
        \XShoppingSt\MpTimeDelivery\Model\TimeSlotOrderFactory $timeSlotOrderFactory,
        \XShoppingSt\MpTimeDelivery\Model\TimeSlotConfigFactory $timeSlotConfigFactory,
        \XShoppingSt\MpTimeDelivery\Helper\Data $helper,
        SessionManager $session
    ) {
        $this->_dateTime = $dateTime;
        $this->_timeSlotOrderFactory = $timeSlotOrderFactory;
        $this->_timeSlotConfigFactory = $timeSlotConfigFactory;
        $this->helper = $helper;
        $this->_session = $session;
    }

    /**
     * after place order event handler
     * Distribute Shipping Price for sellers
     *
     * @param  \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if ($this->helper->getConfigData('active')) {
            $order = $observer->getOrder();
            $lastOrderId = $observer->getOrder()->getId();
            $sellerData = $this->_session->getSellerSlotInfo();
            if ($sellerData) {
                foreach ($sellerData as $value) {
                    $this->updateSellerSlot($value, $lastOrderId);
                }
            }
        }
    }

    /**
     * Save order details with selected slot
     *
     * @param $value
     * @param $lastOrderId
     */
    protected function updateSellerSlot($value, $lastOrderId)
    {
        $slotId = $value['slot_id'];
        $date = $value['date'];
        $sellerId = $this->getActualSellerId($value['id']);
        $model = $this->_timeSlotOrderFactory->create();
        $model->setSellerId($sellerId);
        $model->setSlotId($slotId);
        $model->setOrderId($lastOrderId);
        $model->setSelectedDate($this->_dateTime->gmtDate('Y-m-d', $date));
        $model->save();
    }

    /**
     * Retrieve original seller id
     *
     * @param int $id
     * @param int $sellerId
     */
    protected function getActualSellerId($id)
    {
        $collection = $this->_timeSlotConfigFactory->create()
            ->getCollection()
            ->addFieldToFilter('seller_id', ['eq'=> $id]);

        if ($collection->getSize()) {
            return $id;
        }
        return 0;
    }
}
