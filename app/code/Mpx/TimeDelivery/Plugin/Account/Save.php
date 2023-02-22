<?php
namespace Mpx\TimeDelivery\Plugin\Account;

use Webkul\MpTimeDelivery\Model\TimeslotConfigRepository;
use Webkul\MpTimeDelivery\Model\ResourceModel\TimeSlotConfig\CollectionFactory;
use Magento\Framework\Stdlib\DateTime\DateTime;

class Save
{
    /**
     * define week day value
     *
     * @var string[]
     */
    protected $days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var TimeslotConfigRepository
     */
    protected $timeslotConfigRepository;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;

    /**
     * @var DateTime
     */
    protected $dateTime;

    public function __construct(
        CollectionFactory $collectionFactory,
        TimeslotConfigRepository $timeslotConfigRepository,
        DateTime $dateTime,
        \Magento\Framework\Message\ManagerInterface $messageManager
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->timeslotConfigRepository = $timeslotConfigRepository;
        $this->dateTime = $dateTime;
        $this->messageManager = $messageManager;
    }

    /**
     * Change param timedelivery action save account
     *
     * @param \Webkul\MpTimeDelivery\Controller\Account\Save $subject
     *
     * @return null
     */
    public function beforeExecute(\Webkul\MpTimeDelivery\Controller\Account\Save $subject)
    {
        if ($subject->getRequest()->isPost()) {
            $timeSlotResult = [];
            $timeSlotData = $subject->getRequest()->getParam('timedelivery');
            if (isset($timeSlotData['slot'])) {
                foreach ($timeSlotData['slot'] as $key => $value) {
                    $timeSlotData['slot'][$key]['order_count'] = 99999999;

                    //handle duplicate for delete time slot
                    if (isset($value['is_delete']) && $value['is_delete']) {
                        $timeSlotDelete = $this->getMultipleDataTimeSlotToDelete($value);
                        $timeSlotResult = array_merge_recursive($timeSlotResult,$timeSlotDelete);
                        continue;
                    }

                    if ($this->isExistField($value)) {
                        $this->messageManager->addErrorMessage(__('%1 ~ %2 is already registered', $value['start_time'], $value['end_time']));
                        continue;
                    }

                    if (isset($value['entity_id']) && $value['entity_id'] == '') { //handle duplicate for create new time slot
                        $dulicateData = $timeSlotData['slot'][$key];
                        $createData = $this->getMultipleDataTimeSlotToCreate($dulicateData);
                        $timeSlotResult = array_merge_recursive($timeSlotResult,$createData);
                    } else { //handle case for update
                        $timeSlotUpdate = $this->getDataTimeSlotToUpdate($value);
                        $timeSlotResult = array_merge_recursive($timeSlotResult, $timeSlotUpdate);
                    }

                }
            }
            $timeSlotData['slot'] = $timeSlotResult;
            $subject->getRequest()->setParam('timedelivery', $timeSlotData);
        }
        return null;
    }

    /**
     * @param $data
     * @return bool
     */
    private function isExistField($data)
    {
        $collection = $this->collectionFactory->create();
        $timeSlotCollection = $this->addFilterToByData($collection, $data);
        if ($timeSlotCollection->getSize() >= count($this->days)) {
            if (isset($data['entity_id']) && $data['entity_id'] && $data['entity_id'] == $timeSlotCollection->getFirstItem()->getEntityId()) {
                return false;
            }
            return true;
        }

        return false;
    }

    private function getDataTimeSlotToUpdate($value)
    {
        $timeSlotToUpdate = [];
        $currentTimeSlotConfig = $this->timeslotConfigRepository->getById($value['entity_id']);
        $timeSlotCollection = $this->addFilterToByData($this->collectionFactory->create(), $currentTimeSlotConfig->getData());
        foreach ($timeSlotCollection->getData() as $timeSlotUpdate) {
            $timeSlotUpdate['start_time'] = $value['start_time'];
            $timeSlotUpdate['end_time'] = $value['end_time'];
            $timeSlotUpdate['is_delete'] = $value['is_delete'];
            array_push($timeSlotToUpdate, $timeSlotUpdate);
        }
        return $timeSlotToUpdate;
    }

    /**
     * @param $data
     * @return array
     */
    private function getMultipleDataTimeSlotToCreate($data)
    {
        $timeSlotToCreate = [];
        foreach ($this->days as $day) {
            $newData = $data;
            $newData['delivery_day'] = $day;
            array_push($timeSlotToCreate, $newData);
        }
        return $timeSlotToCreate;
    }

    /**
     * @param $value
     * @return array
     */
    private function getMultipleDataTimeSlotToDelete($data)
    {
        $timeSlotToDelete = [];
        $timeSlotCollection = $this->collectionFactory->create();
        $timeSlotDeleteCollection = $this->addFilterToByData($timeSlotCollection, $data);
        foreach ($timeSlotDeleteCollection->getData() as $timeSlotDelete) {
            $timeSlotDelete['is_delete'] = 1;
            array_push($timeSlotToDelete, $timeSlotDelete);
        }
        return $timeSlotToDelete;
    }

    /**
     * @param $collection
     * @param $filterData
     * @return mixed
     */
    private function addFilterToByData($collection, $filterData)
    {
        $collection->addFieldToFilter('seller_id',['eq' => $filterData['seller_id']])
            ->addFieldToFilter('start_time', ['eq' => $this->dateTime->gmtDate('H:i', $filterData['start_time'])])
            ->addFieldToFilter('end_time', ['eq' => $this->dateTime->gmtDate('H:i', $filterData['end_time'])]);
        return $collection;
    }
}
