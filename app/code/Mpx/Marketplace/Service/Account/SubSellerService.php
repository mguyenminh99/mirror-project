<?php


namespace Mpx\Marketplace\Service\Account;

use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Model\CustomerExtractor;
use XShoppingSt\Marketplace\Model\SellerFactory;

class SubSellerService
{

    /**
     * @var AccountManagementInterface
     */
    protected $accountManagement;

    /**
     * @var CustomerExtractor
     */
    protected $customerExtractor;

    /**
     * @var SellerFactory
     */
    protected $sellerFactory;

    /**
     * @param SellerFactory $sellerFactory
     * @param CustomerExtractor $customerExtractor
     * @param AccountManagementInterface $accountManagement
     */
    public function __construct(
        SellerFactory               $sellerFactory,
        CustomerExtractor           $customerExtractor,
        AccountManagementInterface  $accountManagement
    )
    {
        $this->sellerFactory = $sellerFactory;
        $this->customerExtractor = $customerExtractor;
        $this->accountManagement = $accountManagement;
    }

    /**
     * @param $params
     * @param $currentSeller
     * @param $sellerId
     * @return bool
     */
    public function saveSubSeller($params, $currentSeller, $sellerId)
    {
        try {
            $customer = $this->customerExtractor->extract('customer_account_create', $params);
            $customer = $this->accountManagement
                ->createAccount($customer, $params->getParam('password'));
            $newSeller = $this->sellerFactory->create()->load($currentSeller->getEntityId());
            $newSeller->isObjectNew(true);
            $newSeller->setEntityId(null);
            $newSeller->setCreatedAt(null);
            $newSeller->setSellerId($customer->getId());
            $newSeller->setParentSellerId($sellerId);
            $newSeller->save();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
