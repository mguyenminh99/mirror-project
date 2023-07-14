<?php
namespace XShoppingSt\MpMassUpload\Controller\Dataflow;

class Profile extends \XShoppingSt\MpMassUpload\Controller\Dataflow\AbstractProfile
{
    /**
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $resultPage = $this->_resultPageFactory->create();
        if ($this->marketplaceHelper->getIsSeparatePanel()) {
            $resultPage->addHandle('mpmassupload_layout2_dataflow_profile');
        }
        $resultPage->getConfig()->getTitle()->set(__('Marketplace Mass Upload Dataflow Profile'));
        return $resultPage;
    }
}
