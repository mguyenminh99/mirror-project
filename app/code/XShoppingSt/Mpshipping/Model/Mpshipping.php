<?php
namespace XShoppingSt\Mpshipping\Model;

use XShoppingSt\Mpshipping\Api\Data\MpshippingInterface;
use Magento\Framework\DataObject\IdentityInterface;
use \Magento\Framework\Model\AbstractModel;

class Mpshipping extends AbstractModel implements MpshippingInterface, IdentityInterface
{
    /**
     * CMS page cache tag
     */
    const CACHE_TAG = 'x_shopping_st_shipping';

    /**
     * @var string
     */
    protected $_cacheTag = 'x_shopping_st_shipping';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'x_shopping_st_shipping';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\XShoppingSt\Mpshipping\Model\ResourceModel\Mpshipping::class);
    }
    /**
     * Return unique ID(s) for each object in system
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getMpshippingId()];
    }
    /**
     * Get ID
     *
     * @return int|null
     */
    public function getMpshippingId()
    {
        return $this->getData(self::MPSHIPPING_ID);
    }
    public function setMpshippingId($id)
    {
        return $this->setData(self::MPSHIPPING_ID, $id);
    }
}
