<?php
namespace XShoppingSt\Marketplace\Plugin\CustomerData;

class Customer
{
    /**
     * @var \Magento\Customer\Helper\Session\CurrentCustomer
     */
    protected $currentCustomer;

    /**
     * @var \Magento\Customer\Helper\View
     */
    private $customerViewHelper;

    /**
     * @param \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer
     * @param \Magento\Customer\Helper\View                    $customerViewHelper
     */
    public function __construct(
        \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer,
        \Magento\Customer\Helper\View $customerViewHelper
    ) {
        $this->currentCustomer = $currentCustomer;
        $this->customerViewHelper = $customerViewHelper;
    }

    public function afterGetSectionData(\Magento\Customer\CustomerData\Customer $subject, $result)
    {
        if ($this->currentCustomer->getCustomerId()) {
            $customer = $this->currentCustomer->getCustomer();
            $result['email'] = $customer->getEmail();
        }
        return $result;
    }
}
