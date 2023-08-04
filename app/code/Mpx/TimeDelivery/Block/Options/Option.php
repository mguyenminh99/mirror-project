<?php

namespace Mpx\TimeDelivery\Block\Options;
use Magento\Customer\Model\SessionFactory;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\View\Element\Template\Context;
use XShoppingSt\MpTimeDelivery\Helper\Data;
use XShoppingSt\MpTimeDelivery\Model\Config\Source\Days;
use XShoppingSt\MpTimeDelivery\Model\ResourceModel\TimeSlotConfig\CollectionFactory;
use XShoppingSt\Marketplace\Helper\Data as MarketplaceHelperData;


class Option extends \XShoppingSt\MpTimeDelivery\Block\Options\Option
{
    /**
     * @var MarketplaceHelperData
     */
    public $marketplaceHelperData;

    /**
     * @var string
     */
    protected $_template = 'XShoppingSt_MpTimeDelivery::account/options/option.phtml';

    /**
     * @param MarketplaceHelperData $marketplaceHelperData
     * @param Context $context
     * @param CollectionFactory $timeSlotCollection
     * @param SessionFactory $customerSessionFactory
     * @param Days $days
     * @param DateTime $dateTime
     * @param Data $helper
     * @param array $data
     */
    public function __construct(
        MarketplaceHelperData $marketplaceHelperData,
        Context $context,
        CollectionFactory $timeSlotCollection,
        SessionFactory $customerSessionFactory,
        Days $days, DateTime $dateTime,
        Data $helper,
        array $data = []
    )
    {
        $this->marketplaceHelperData = $marketplaceHelperData;
        parent::__construct($context, $timeSlotCollection, $customerSessionFactory, $days, $dateTime, $helper, $data);
    }

    /**
     * Provide already save values
     *
     * @return array
     */
    public function getTimeSlotsValue()
    {
        $customerId = $this->marketplaceHelperData->getCustomerId();
        $collection = $this->timeSlotCollection->create()
            ->addFieldToFilter(
                'seller_id',
                ['eq' => $customerId]
            );
        $collection->getSelect()->group('seller_id')->group('start_time')->group('end_time');
        $values = [];
        if ($collection->getSize()) {
            foreach ($collection as $slot) {
                $value = [];
                $value['id'] = $slot->getEntityId();
                $value['entity_id'] = $slot->getEntityId();
                $value['item_count'] = 1;
                $value['seller_id'] = $this->getCurrentCustomerId();
                $value['day'] = $slot->getDeliveryDay();
                $value['start'] = $this->dateTime->gmtDate('h:i A', $slot->getStartTime());
                $value['end'] = $this->dateTime->gmtDate('h:i A', $slot->getEndTime());
                $value['quota'] = $slot->getOrderCount();
                $values[] = $this->helper->getJson()->serialize($value);
            }
        }

        return $values;
    }
}
