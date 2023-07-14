<?php
namespace XShoppingSt\Marketplace\Controller\Adminhtml\Seller;

class Flags extends \Magento\Customer\Controller\Adminhtml\Index
{
    /**
     * Get Seller's Flags list
     *
     * @return \Magento\Framework\View\Result\Layout
     */
    public function execute()
    {
        $customerId = $this->initCurrentCustomer();
        $resultLayout = $this->resultLayoutFactory->create();
        $block = $resultLayout->getLayout()->getBlock('admin.seller.flags');
        $block->setCustomerId($customerId)->setUseAjax(true);
        return $resultLayout;
    }
}
