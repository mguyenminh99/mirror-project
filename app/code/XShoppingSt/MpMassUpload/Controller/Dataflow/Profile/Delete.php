<?php
namespace XShoppingSt\MpMassUpload\Controller\Dataflow\Profile;

/**
 * XShoppingSt MassUpload Dataflow Profile Delete Controller.
 */
class Delete extends \XShoppingSt\MpMassUpload\Controller\Dataflow\AbstractProfile
{
    /**
     * MassUpload Dataflow Profile Delete action.
     *
     * @return \Magento\Framework\Controller\Result\RedirectFactory
     */
    public function execute()
    {
        try {
            $sellerId = $this->marketplaceHelper->getCustomerId();
            $id = (int)$this->getRequest()->getParam('id');
            // Check If profile does not exists
            $attributeProfile = $this->_attributeProfileRepository->get($id);
            if ($attributeProfile->getId()) {
                if ($attributeProfile->getSellerId() == $sellerId) {
                    $this->_attributeProfileRepository->deleteById($id);
                    $this->messageManager->addSuccess(
                        __('Profile was successfully deleted.')
                    );
                } else {
                    $this->messageManager->addError(
                        __('You are not authorized to delete this profile.')
                    );
                }
            } else {
                $this->messageManager->addError(
                    __('Requested profile doesn\'t exist')
                );
            }
            return $this->resultRedirectFactory->create()->setPath(
                'mpmassupload/dataflow/profile',
                ['_secure' => $this->getRequest()->isSecure()]
            );
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());

            return $this->resultRedirectFactory->create()->setPath(
                'mpmassupload/dataflow/profile',
                ['_secure' => $this->getRequest()->isSecure()]
            );
        }
    }
}
