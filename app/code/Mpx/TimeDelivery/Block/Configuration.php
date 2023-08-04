<?php

namespace Mpx\TimeDelivery\Block;

use Magento\Framework\View\Element\Template\Context;
use Magento\Customer\Model\SessionFactory;
use XShoppingSt\MpTimeDelivery\Helper\Data as Helper;
use Mpx\Marketplace\Helper\CommonFunc;
use Magento\Customer\Model\CustomerFactory;

class Configuration extends \XShoppingSt\MpTimeDelivery\Block\Configuration
{
    /**
     * @var CustomerFactory
     */
    protected $customerFactory;

    /**
     * @var CommonFunc
     */
    protected $mpxHelper;

    /**
     * @var Magento\Customer\Model\SessionFactory
     */
    protected $customerSessionFactory;

    /**
     * @var Helper
     */
    protected $helper;

    /**
     * @param Context $context
     * @param SessionFactory $customerSessionFactory
     * @param Helper $helper
     * @param array $data
     */
    public function __construct(
        CustomerFactory $customerFactory,
        CommonFunc $mpxHelper,
        Context $context,
        SessionFactory $customerSessionFactory,
        Helper $helper,
        array $data = []
    )
    {
        $this->customerFactory = $customerFactory;
        $this->mpxHelper = $mpxHelper;
        parent::__construct($context, $customerSessionFactory, $helper, $data);
    }

    /**
     * @return \Magento\Customer\Model\Customer
     */
    public function _getCustomerData()
    {
        $currentCustomerId = $this->customerSessionFactory->create()->getCustomer()->getEntityId();
        $originSellerId = $this->mpxHelper->getOriginSellerId($currentCustomerId);
        return $this->customerFactory->create()->load($originSellerId);
    }
}
