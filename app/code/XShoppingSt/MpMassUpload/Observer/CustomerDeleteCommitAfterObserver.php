<?php
namespace XShoppingSt\MpMassUpload\Observer;

use Magento\Framework\Event\ObserverInterface;
use XShoppingSt\MpMassUpload\Model\AttributeProfileFactory;

/**
 * XShoppingSt MpMassUpload CustomerDeleteCommitAfterObserver Observer Model.
 */
class CustomerDeleteCommitAfterObserver implements ObserverInterface
{
    /**
     * @var XShoppingSt\MpMassUpload\Model\AttributeProfileFactory
     */
    protected $attributeProfile;

    /**
     * @param AttributeProfileFactory $attributeProfile
     */
    public function __construct(
        AttributeProfileFactory $attributeProfile
    ) {
        $this->attributeProfile = $attributeProfile;
    }

    /**
     * customer Delete After event handler.
     *
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        try {
            $customer = $observer->getCustomer();
            $customerId = $customer->getId();
            $attributeProfile = $this->attributeProfile->create()->getCollection()
            ->addFieldToFilter('seller_id', ['eq' => $customerId]);
            if (!empty($attributeProfile)) {
                foreach ($attributeProfile as $profiles) {
                    $profiles->delete();
                }
            }
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }
    }
}
