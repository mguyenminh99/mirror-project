<?php
namespace XShoppingSt\MpMassUpload\Controller\Adminhtml\Dataflow\Profile;

/**
 * XShoppingSt MassUpload Dataflow Profile Add New Controller.
 */
class NewAction extends \XShoppingSt\MpMassUpload\Controller\Adminhtml\Dataflow\AbstractProfile
{
    /**
     * Create New Dataflow Profile action
     *
     * @return \Magento\Backend\Model\View\Result\Forward
     */
    public function execute()
    {
        $resultForward = $this->resultForwardFactory->create();
        $resultForward->forward('edit');
        return $resultForward;
    }
}
