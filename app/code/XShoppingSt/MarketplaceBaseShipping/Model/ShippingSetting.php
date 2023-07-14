<?php
namespace XShoppingSt\MarketplaceBaseShipping\Model;

use XShoppingSt\MarketplaceBaseShipping\Api\Data\ShippingSettingInterface;
use XShoppingSt\MarketplaceBaseShipping\Model\ResourceModel\ShippingSetting as ResourceShippingSetting;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\App\ObjectManager;

/**
 * Customer data model
 *
 */
class ShippingSetting extends \XShoppingSt\MarketplaceBaseShipping\Model\ShippingSetting\AbstractShippingSetting
{

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\XShoppingSt\MarketplaceBaseShipping\Model\ResourceModel\ShippingSetting::class);
    }
}
