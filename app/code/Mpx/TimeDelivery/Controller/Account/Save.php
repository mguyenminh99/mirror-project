<?php
namespace Mpx\TimeDelivery\Controller\Account;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use XShoppingSt\MpTimeDelivery\Api\Data\TimeslotConfigInterfaceFactory;
use XShoppingSt\MpTimeDelivery\Api\TimeslotConfigRepositoryInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterfaceFactory;
use Magento\Customer\Api\Data\CustomerInterface;
use XShoppingSt\MpTimeDelivery\Model\ResourceModel\TimeSlotConfig\CollectionFactory;
use XShoppingSt\Marketplace\Helper\Data;

class Save extends \XShoppingSt\MpTimeDelivery\Controller\Account\Save
{
    /**
     * @var Data
     */
    protected $helperData;

    /**
     * @param Data $helperData
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param TimeslotConfigRepositoryInterface $timeSlotRepository
     * @param CollectionFactory $timeSlotCollection
     * @param TimeslotConfigInterfaceFactory $timeSlotDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param \Magento\Customer\Model\SessionFactory $customerSessionFactory
     * @param CustomerRepositoryInterface $customerRepository
     * @param \Magento\Customer\Model\Customer\Mapper $customerMapper
     * @param \Magento\Customer\Model\UrlFactory $urlFactory
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $dateTime
     * @param CustomerInterfaceFactory $customerDataFactory
     */
    public function __construct(
        Data $helperData,
        Context $context,
        PageFactory $resultPageFactory,
        TimeslotConfigRepositoryInterface $timeSlotRepository,
        CollectionFactory $timeSlotCollection,
        TimeslotConfigInterfaceFactory $timeSlotDataFactory,
        DataObjectHelper $dataObjectHelper,
        \Magento\Customer\Model\SessionFactory $customerSessionFactory,
        CustomerRepositoryInterface $customerRepository,
        \Magento\Customer\Model\Customer\Mapper $customerMapper,
        \Magento\Customer\Model\UrlFactory $urlFactory,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime,
        CustomerInterfaceFactory $customerDataFactory)
    {
        $this->helperData = $helperData;
        parent::__construct($context, $resultPageFactory, $timeSlotRepository, $timeSlotCollection, $timeSlotDataFactory, $dataObjectHelper, $customerSessionFactory, $customerRepository, $customerMapper, $urlFactory, $dateTime, $customerDataFactory);
    }

    /**
     * Save minimum required time for seller
     *
     * @return [type] [description]
     */
    protected function saveMinimumOrderTime()
    {
        $customerData['minimum_time_required'] = $this->getRequest()->getParam('minimum_time_required');
        if ($customerData['minimum_time_required'] !== '' && is_numeric($customerData['minimum_time_required'])) {
            $customerId = $this->helperData->getCustomerId();
            $savedCustomerData = $this->_customerRepository->getById($customerId);

            $customer = $this->_customerDataFactory->create();

            $customerData = array_merge(
                $this->_customerMapper->toFlatArray($savedCustomerData),
                $customerData
            );
            $customerData['id'] = $customerId;
            $this->_dataObjectHelper->populateWithArray(
                $customer,
                $customerData,
                CustomerInterface::class
            );
            $this->_customerRepository->save($customer);
        }
    }
}
