<?php
namespace Mpx\BaseShipping\Controller\Shipping;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class FormPost extends \XShoppingSt\MarketplaceBaseShipping\Controller\Shipping\FormPost
{
    /**
     * Extract address from request
     *
     * @return \XShoppingSt\MarketplaceBaseShipping\Api\Data\ShippingSettingInterface
     */
    protected function _extractSetting()
    {
        $existingData = $this->getExistingAddressData();
        if (empty($existingData)) {
            $existingData = $this->getRequest()->getParams();
        }

        $this->updateRegionData($existingData);

        $attributeValues = $this->getRequest()->getParams();

        $this->updateRegionData($attributeValues);

        $shippingDataObject = $this->shippingSettingDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $shippingDataObject,
            array_merge($existingData, $attributeValues),
            \XShoppingSt\MarketplaceBaseShipping\Api\Data\ShippingSettingInterface::class
        );

        $shippingDataObject->setSellerId($this->customerSession->getCustomerId());
        $shippingDataObject->setData('b2cloud_billing_customer_code', ($this->getRequest()->getParam('b2cloud_billing_customer_code')));
        $shippingDataObject->setData('b2cloud_billing_classification_code', ($this->getRequest()->getParam('b2cloud_billing_classification_code')));
        $shippingDataObject->setData('b2cloud_fare_control_number', ($this->getRequest()->getParam('b2cloud_fare_control_number')));

        return $shippingDataObject;
    }
}
